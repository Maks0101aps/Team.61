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
use App\Models\User;

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
        
        // Get events associated with the user based on their role
        $userRole = $user->role;
        
        // Query to get events based on user's role or created by the user
        $eventsQuery = Event::where(function($query) use ($user, $userRole) {
                // Include events created by this user
                $query->where('created_by', $user->id);
                
                // Include events based on role
                if ($userRole == User::ROLE_STUDENT) {
                    // For students
                    $query->orWhereHas('students', function($q) use ($user) {
                        $q->where('user_id', $user->id);
                    });
                } elseif ($userRole == User::ROLE_TEACHER) {
                    // For teachers
                    $query->orWhereHas('teachers', function($q) use ($user) {
                        $q->where('user_id', $user->id);
                    });
                } elseif ($userRole == User::ROLE_PARENT) {
                    // For parents
                    $query->orWhereHas('parents', function($q) use ($user) {
                        $q->where('user_id', $user->id);
                    });
                }
            })
            ->where('start_date', '>=', now()->subDays(30));
            
        // Debug log of the query
        \Log::info('Events query: ' . $eventsQuery->toSql());
        \Log::info('User role: ' . $userRole);
        \Log::info('User ID: ' . $user->id);
        
        $events = $eventsQuery->get();
        
        // Log the count of events found
        \Log::info('Found ' . $events->count() . ' events to sync');
        
        $syncedCount = 0;
        
        foreach ($events as $event) {
            // Create Google Calendar event
            $googleEvent = new Google_Service_Calendar_Event([
                'summary' => $event->title,
                'location' => $event->location,
                'description' => $event->content ?? '',
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
                \Log::error('Google Calendar sync error for event ID ' . $event->id . ': ' . $e->getMessage());
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
        
        try {
            // Get up-to-date user info from database to ensure we have account_id
            $freshUser = \DB::table('users')->where('id', $user->id)->first();
            if (!$freshUser || !$freshUser->account_id) {
                return redirect()->route('calendar.settings')
                    ->with('error', 'Could not determine your account. Please contact administrator.');
            }
            
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
                    try {
                        // Create a new school event
                        $newEvent = new Event();
                        $newEvent->title = $googleEvent->getSummary() ?: 'Untitled Event';
                        $newEvent->content = $googleEvent->getDescription();
                        $newEvent->location = $googleEvent->getLocation();
                        $newEvent->start_date = Carbon::parse($googleEvent->getStart()->getDateTime());
                        
                        // Calculate duration in minutes
                        $startTime = Carbon::parse($googleEvent->getStart()->getDateTime());
                        $endTime = Carbon::parse($googleEvent->getEnd()->getDateTime());
                        $newEvent->duration = $startTime->diffInMinutes($endTime);
                        
                        $newEvent->type = 'personal'; // Default type for imported events
                        $newEvent->created_by = $user->id; // Creator ID
                        $newEvent->account_id = $freshUser->account_id; // Required account ID field
                        $newEvent->google_calendar_id = $googleEvent->getId(); // Store Google Calendar ID
                        
                        // Debug information for the Event model
                        \Log::info('Event model fillable: ' . implode(', ', $newEvent->getFillable()));
                        \Log::info('Creating new event with data: ' . json_encode([
                            'title' => $newEvent->title,
                            'content' => $newEvent->content,
                            'location' => $newEvent->location,
                            'start_date' => $newEvent->start_date,
                            'duration' => $newEvent->duration,
                            'type' => $newEvent->type,
                            'created_by' => $newEvent->created_by,
                            'account_id' => $newEvent->account_id,
                            'google_calendar_id' => $newEvent->google_calendar_id
                        ]));
                        
                        $newEvent->save();
                        
                        // Attach user to the event based on your app's structure
                        // Example: $newEvent->teachers()->attach($user->id);
                        
                        $importedCount++;
                    } catch (\Exception $e) {
                        \Log::error('Error creating event from Google Calendar: ' . $e->getMessage());
                        \Log::error('Event data: ' . json_encode([
                            'title' => $googleEvent->getSummary(),
                            'start' => $googleEvent->getStart()->getDateTime(),
                            'end' => $googleEvent->getEnd()->getDateTime()
                        ]));
                    }
                }
            }
            
            return redirect()->route('calendar.settings')
                ->with('success', "Successfully imported {$importedCount} events from Google Calendar");
                
        } catch (\Exception $e) {
            \Log::error('Google Calendar sync error: ' . $e->getMessage());
            return redirect()->route('calendar.settings')
                ->with('error', 'Error syncing from Google Calendar: ' . $e->getMessage());
        }
    }

    /**
     * Show calendar settings/integration page
     */
    public function settings()
    {
        $user = Auth::user();
        $isConnected = !empty($user->google_calendar_token);
        $language = session('language', 'uk');
        
        return Inertia::render('Calendar/Settings', [
            'isGoogleConnected' => $isConnected,
            'language' => $language
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
        
        // Set client credentials directly from environment variables
        $clientId = env('GOOGLE_CALENDAR_CLIENT_ID');
        $clientSecret = env('GOOGLE_CALENDAR_CLIENT_SECRET');
        
        if (!empty($clientId) && !empty($clientSecret)) {
            $client->setClientId($clientId);
            $client->setClientSecret($clientSecret);
        } else {
            // Check if credentials file exists as fallback
            $credentialsPath = storage_path('app/google-calendar/credentials.json');
            if (file_exists($credentialsPath)) {
                $client->setAuthConfig($credentialsPath);
            } else {
                \Log::error('Google Calendar credentials not found: Neither environment variables nor credentials file exists');
                throw new \Exception('Google Calendar credentials not configured correctly');
            }
        }
        
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');
        
        // Ensure we're using the correct redirect URI for the environment
        $redirectUri = env('GOOGLE_CALENDAR_REDIRECT_URI', url('/google-calendar/callback'));
        $client->setRedirectUri($redirectUri);
        
        return $client;
    }
}
