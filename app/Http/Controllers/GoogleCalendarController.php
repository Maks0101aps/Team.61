<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use App\Models\Event;
use Carbon\Carbon;

class GoogleCalendarController extends Controller
{
    /**
     * Redirect to Google OAuth consent screen
     */
    public function redirectToGoogle()
    {
        try {
            $client = $this->getClient();
            $authUrl = $client->createAuthUrl();
            
            return redirect($authUrl);
        } catch (\Exception $e) {
            \Log::error('Google Calendar redirect error: ' . $e->getMessage());
            
            return redirect()->route('calendar.settings')
                ->with('error', 'Error connecting to Google Calendar: ' . $e->getMessage());
        }
    }

    /**
     * Google OAuth callback handler
     */
    public function handleGoogleCallback(Request $request)
    {
        try {
            $client = $this->getClient();
            
            // Get the authorization code from the request
            if (!$request->has('code')) {
                return redirect()->route('calendar.settings')
                    ->with('error', 'No authorization code received from Google');
            }
            
            $code = $request->code;
            
            // Set the correct redirect URI before fetching the token
            $client->setRedirectUri(url('/google-calendar/callback'));
            
            // Exchange authorization code for an access token
            $token = $client->fetchAccessTokenWithAuthCode($code);
            
            if (isset($token['error'])) {
                return redirect()->route('calendar.settings')
                    ->with('error', 'Google Calendar authentication failed: ' . 
                        ($token['error_description'] ?? $token['error']));
            }
            
            // Store the token in the user's session or database
            $user = Auth::user();
            $user->google_calendar_token = json_encode($token);
            $user->save();
            
            return redirect()->route('calendar.settings')
                ->with('success', 'Google Calendar connected successfully!');
                
        } catch (\Exception $e) {
            \Log::error('Google Calendar OAuth error: ' . $e->getMessage());
            return redirect()->route('calendar.settings')
                ->with('error', 'Error connecting to Google Calendar: ' . $e->getMessage());
        }
    }

    /**
     * Sync events from school calendar to Google Calendar
     */
    public function syncToGoogle()
    {
        $user = Auth::user();
        
        // Check if user has connected Google Calendar
        if (empty($user->google_calendar_token)) {
            return redirect()->route('calendar.settings')->with('error', 'Please connect your Google Calendar first');
        }
        
        $client = $this->getClient();
        
        // Set the access token
        $accessToken = json_decode($user->google_calendar_token, true);
        $client->setAccessToken($accessToken);
        
        // Refresh the token if it's expired
        if ($client->isAccessTokenExpired()) {
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                $user->google_calendar_token = json_encode($client->getAccessToken());
                $user->save();
            } else {
                return redirect()->route('calendar.settings')->with('error', 'Google Calendar token expired. Please reconnect.');
            }
        }
        
        // Get Google Calendar service
        $service = new Google_Service_Calendar($client);
        
        // Get school events for the user
        $events = Event::where(function($query) use ($user) {
                // Get events where the user is a participant (based on your app's logic)
                $query->whereHas('students', function($q) use ($user) {
                    $q->where('user_id', $user->id);
                })
                ->orWhereHas('teachers', function($q) use ($user) {
                    $q->where('user_id', $user->id);
                })
                ->orWhereHas('parents', function($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            })
            ->where('start_date', '>=', now()->subDays(30))
            ->get();
        
        $syncedCount = 0;
        
        foreach ($events as $event) {
            // Create Google Calendar event
            $googleEvent = new Google_Service_Calendar_Event([
                'summary' => $event->title,
                'location' => $event->location,
                'description' => $event->description,
                'start' => [
                    'dateTime' => Carbon::parse($event->start_date)->toIso8601String(),
                    'timeZone' => config('app.timezone'),
                ],
                'end' => [
                    'dateTime' => Carbon::parse($event->start_date)
                        ->addMinutes($event->duration)
                        ->toIso8601String(),
                    'timeZone' => config('app.timezone'),
                ],
            ]);
            
            try {
                // Insert the event in the user's primary calendar
                $service->events->insert('primary', $googleEvent);
                $syncedCount++;
            } catch (\Exception $e) {
                // Log error
                \Log::error('Google Calendar sync error: ' . $e->getMessage());
            }
        }
        
        return redirect()->route('calendar.settings')
            ->with('success', "Successfully synced {$syncedCount} events to Google Calendar");
    }

    /**
     * Sync events from Google Calendar to school calendar
     */
    public function syncFromGoogle()
    {
        $user = Auth::user();
        
        // Check if user has connected Google Calendar
        if (empty($user->google_calendar_token)) {
            return redirect()->route('calendar.settings')->with('error', 'Please connect your Google Calendar first');
        }
        
        $client = $this->getClient();
        
        // Set the access token
        $accessToken = json_decode($user->google_calendar_token, true);
        $client->setAccessToken($accessToken);
        
        // Refresh the token if it's expired
        if ($client->isAccessTokenExpired()) {
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                $user->google_calendar_token = json_encode($client->getAccessToken());
                $user->save();
            } else {
                return redirect()->route('calendar.settings')->with('error', 'Google Calendar token expired. Please reconnect.');
            }
        }
        
        // Get Google Calendar service
        $service = new Google_Service_Calendar($client);
        
        // Get events from Google Calendar
        $optParams = [
            'maxResults' => 100,
            'orderBy' => 'startTime',
            'singleEvents' => true,
            'timeMin' => Carbon::now()->subDays(7)->toIso8601String(),
            'timeMax' => Carbon::now()->addDays(30)->toIso8601String(),
        ];
        
        $results = $service->events->listEvents('primary', $optParams);
        $googleEvents = $results->getItems();
        
        $importedCount = 0;
        
        foreach ($googleEvents as $googleEvent) {
            // Skip events without start time (all-day events)
            if (!$googleEvent->getStart()->getDateTime()) {
                continue;
            }
            
            // Check if this event was already imported (by event ID or similar logic)
            $existingEvent = Event::where('google_calendar_id', $googleEvent->getId())->first();
            
            if (!$existingEvent) {
                // Create a new school event
                $newEvent = new Event();
                $newEvent->title = $googleEvent->getSummary();
                $newEvent->description = $googleEvent->getDescription();
                $newEvent->location = $googleEvent->getLocation();
                $newEvent->start_date = Carbon::parse($googleEvent->getStart()->getDateTime());
                
                // Calculate duration in minutes
                $startTime = Carbon::parse($googleEvent->getStart()->getDateTime());
                $endTime = Carbon::parse($googleEvent->getEnd()->getDateTime());
                $newEvent->duration = $startTime->diffInMinutes($endTime);
                
                $newEvent->type = 'personal'; // Default type for imported events
                $newEvent->user_id = $user->id; // Creator ID
                $newEvent->google_calendar_id = $googleEvent->getId(); // Store Google Calendar ID
                
                $newEvent->save();
                
                // Attach user to the event based on your app's structure
                // Example: $newEvent->teachers()->attach($user->id);
                
                $importedCount++;
            }
        }
        
        return redirect()->route('calendar.settings')
            ->with('success', "Successfully imported {$importedCount} events from Google Calendar");
    }

    /**
     * Show calendar settings/integration page
     */
    public function settings()
    {
        $user = Auth::user();
        $isConnected = !empty($user->google_calendar_token);
        
        return Inertia::render('Calendar/Settings', [
            'isGoogleConnected' => $isConnected
        ]);
    }

    /**
     * Disconnect Google Calendar
     */
    public function disconnect()
    {
        $user = Auth::user();
        $user->google_calendar_token = null;
        $user->save();
        
        return redirect()->route('calendar.settings')
            ->with('success', 'Google Calendar disconnected successfully');
    }

    /**
     * Get a Google API client instance
     */
    private function getClient()
    {
        $client = new Google_Client();
        $client->setApplicationName(config('app.name'));
        $client->setScopes([Google_Service_Calendar::CALENDAR]);
        
        // Check if credentials file exists
        $credentialsPath = storage_path('app/google-calendar/credentials.json');
        if (file_exists($credentialsPath)) {
            $client->setAuthConfig($credentialsPath);
        } else {
            // Fallback to environment variables if file doesn't exist
            $client->setClientId(env('GOOGLE_CALENDAR_CLIENT_ID'));
            $client->setClientSecret(env('GOOGLE_CALENDAR_CLIENT_SECRET'));
        }
        
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');
        
        // Ensure we're using the correct redirect URI for the environment
        $redirectUri = env('GOOGLE_CALENDAR_REDIRECT_URI', url('/google-calendar/callback'));
        $client->setRedirectUri($redirectUri);
        
        return $client;
    }
}
