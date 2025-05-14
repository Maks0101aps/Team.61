<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class TeachersController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Teachers/Index', [
            'filters' => Request::all('search', 'trashed'),
            'teachers' => Auth::user()->account->teachers()
                ->with('organization')
                ->orderByName()
                ->filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($teacher) => [
                    'id' => $teacher->id,
                    'name' => $teacher->name,
                    'phone' => $teacher->phone,
                    'city' => $teacher->city,
                    'subject' => $teacher->subject,
                    'qualification' => $teacher->qualification,
                    'deleted_at' => $teacher->deleted_at,
                    'organization' => $teacher->organization ? $teacher->organization->only('name') : null,
                    'photo' => $this->getTeacherPhoto($teacher),
                ]),
        ]);
    }

    public function create(): Response
    {
        $user = Auth::user();
        
        // Перевіряємо, чи є користувач учнем
        if ($user->isStudent()) {
            return Redirect::route('dashboard')->with('error', 'Учні не можуть створювати нових вчителів.');
        }
        
        // Перевіряємо, чи є користувач батьком
        if ($user->isParent()) {
            return Redirect::route('dashboard')->with('error', 'Батьки не можуть створювати вчителів.');
        }
        
        return Inertia::render('Teachers/Create', [
            'subjects' => Teacher::getSubjects(),
            'qualifications' => Teacher::getQualifications(),
            'regions' => Teacher::getRegions(),
        ]);
    }

    public function store(): RedirectResponse
    {
        $user = Auth::user();
        
        // Перевіряємо, чи є користувач учнем
        if ($user->isStudent()) {
            return Redirect::route('dashboard')->with('error', 'Учні не можуть створювати нових вчителів.');
        }
        
        // Перевіряємо, чи є користувач батьком
        if ($user->isParent()) {
            return Redirect::route('dashboard')->with('error', 'Батьки не можуть створювати вчителів.');
        }
        
        $validatedData = Request::validate([
            'first_name' => ['required', 'max:50'],
            'middle_name' => ['required', 'max:50'],
            'last_name' => ['required', 'max:50'],
            'email' => ['nullable', 'max:50', 'email'],
            'phone' => ['nullable', 'string', 'regex:/^\+380[0-9]{9}$/', 'max:13'],
            'subject' => ['nullable', 'max:100'],
            'qualification' => ['nullable', 'max:100'],
            'address' => ['nullable', 'max:150'],
            'city' => ['nullable', 'max:50'],
            'region' => ['nullable', 'max:50'],
            /*'postal_code' => ['nullable', 'max:25'],*/
        ]);

        // Always set the country to UA
        $validatedData['country'] = 'UA';
        $validatedData['organization_id'] = null;

        Auth::user()->account->teachers()->create($validatedData);

        return Redirect::route('teachers.index')->with('success', 'Teacher created.');
    }

    public function edit(Teacher $teacher): Response
    {
        $user = Auth::user();
        
        // Prevent students and parents from editing teachers
        if ($user->isStudent() || $user->isParent()) {
            return Redirect::route('teachers.index')->with('error', 'У вас немає доступу до редагування вчителів.');
        }
        
        // Get cities based on the teacher's region if region is set
        $cities = $teacher->region ? Teacher::getCitiesByRegion($teacher->region) : [];
        
        return Inertia::render('Teachers/Edit', [
            'teacher' => [
                'id' => $teacher->id,
                'first_name' => $teacher->first_name,
                'middle_name' => $teacher->middle_name,
                'last_name' => $teacher->last_name,
                'organization_id' => $teacher->organization_id,
                'email' => $teacher->email,
                'phone' => $teacher->phone,
                'subject' => $teacher->subject,
                'qualification' => $teacher->qualification,
                'address' => $teacher->address,
                'city' => $teacher->city,
                'region' => $teacher->region,
                'country' => $teacher->country,
                'postal_code' => $teacher->postal_code,
                'deleted_at' => $teacher->deleted_at,
                'photo' => $this->getTeacherPhoto($teacher),
            ],
            'subjects' => Teacher::getSubjects(),
            'qualifications' => Teacher::getQualifications(),
            'regions' => Teacher::getRegions(),
            'cities' => $cities,
        ]);
    }

    public function update(Teacher $teacher): RedirectResponse
    {
        $user = Auth::user();
        
        // Prevent students and parents from updating teachers
        if ($user->isStudent() || $user->isParent()) {
            return Redirect::route('teachers.index')->with('error', 'У вас немає доступу до оновлення вчителів.');
        }
        
        try {
            // Log incoming data
            \Log::info('Teacher update request data:', [
                'request_all' => Request::all(),
                'teacher_before' => $teacher->toArray()
            ]);
            
            $validatedData = Request::validate([
                'first_name' => ['required', 'max:50'],
                'middle_name' => ['required', 'max:50'],
                'last_name' => ['required', 'max:50'],
                'email' => ['nullable', 'max:50', 'email'],
                'phone' => ['nullable', 'string', 'regex:/^\+?380[0-9]{9}$/', 'max:13'], // Made + optional
                'subject' => ['nullable', 'max:100'],
                'qualification' => ['nullable', 'max:100'],
                'address' => ['nullable', 'max:150'],
                'city' => ['nullable', 'max:50'],
                'region' => ['nullable', 'max:50'],
                'postal_code' => ['nullable', 'max:25'],
                'avatar' => ['nullable', 'image', 'max:2048'],
            ]);

            // Log validated data
            \Log::info('Validated data for teacher update:', $validatedData);
            
            // Always set country to UA and organization_id to null
            $validatedData['country'] = 'UA';
            $validatedData['organization_id'] = null;
            
            // Remove avatar from validated data
            if (isset($validatedData['avatar'])) {
                unset($validatedData['avatar']);
            }

            // Format phone with + if missing
            if (!empty($validatedData['phone'])) {
                if (strpos($validatedData['phone'], '+') !== 0) {
                    $validatedData['phone'] = '+' . $validatedData['phone'];
                }
            }

            // Force update with explicit field assignments
            $teacher->first_name = $validatedData['first_name'];
            $teacher->middle_name = $validatedData['middle_name'];
            $teacher->last_name = $validatedData['last_name'];
            $teacher->email = $validatedData['email'] ?? $teacher->email;
            $teacher->phone = $validatedData['phone'] ?? $teacher->phone;
            $teacher->subject = $validatedData['subject'] ?? $teacher->subject;
            $teacher->qualification = $validatedData['qualification'] ?? $teacher->qualification;
            $teacher->address = $validatedData['address'] ?? $teacher->address;
            $teacher->city = $validatedData['city'] ?? $teacher->city;
            $teacher->region = $validatedData['region'] ?? $teacher->region;
            $teacher->country = $validatedData['country'];
            $teacher->organization_id = $validatedData['organization_id'];
            $teacher->postal_code = $validatedData['postal_code'] ?? $teacher->postal_code;
            $teacher->save();
            
            // Log teacher data after update
            \Log::info('Teacher after update:', [
                'teacher_after' => $teacher->fresh()->toArray()
            ]);

            // Handle avatar upload
            if (Request::hasFile('avatar')) {
                $user = User::where('email', $teacher->email)->first();
                
                if ($user) {
                    if ($user->photo_path) {
                        $oldPath = storage_path('app/public/' . $user->photo_path);
                        if (file_exists($oldPath)) {
                            unlink($oldPath);
                        }
                    }

                    $path = Request::file('avatar')->store('teachers-avatars', 'public');
                    $user->update(['photo_path' => $path]);
                }
            }
            
            // Also update the corresponding user if email is the same
            if ($teacher->email) {
                $userToUpdate = User::where('email', $teacher->email)->first();
                if ($userToUpdate) {
                    $userToUpdate->first_name = $teacher->first_name;
                    $userToUpdate->middle_name = $teacher->middle_name;
                    $userToUpdate->last_name = $teacher->last_name;
                    $userToUpdate->save();
                }
            }

            return Redirect::back()->with('success', 'Інформацію про вчителя оновлено.');
            
        } catch (\Exception $e) {
            // Log any exceptions
            \Log::error('Error updating teacher:', ['exception' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return Redirect::back()->with('error', 'Помилка при оновленні даних: ' . $e->getMessage());
        }
    }

    public function destroy(Teacher $teacher): RedirectResponse
    {
        $user = Auth::user();
        
        // Prevent students and parents from deleting teachers
        if ($user->isStudent() || $user->isParent()) {
            return Redirect::route('teachers.index')->with('error', 'У вас немає доступу до видалення вчителів.');
        }
        
        $teacher->delete();

        return Redirect::back()->with('success', 'Teacher deleted.');
    }

    public function restore(Teacher $teacher): RedirectResponse
    {
        $user = Auth::user();
        
        // Prevent students and parents from restoring teachers
        if ($user->isStudent() || $user->isParent()) {
            return Redirect::route('teachers.index')->with('error', 'У вас немає доступу до відновлення вчителів.');
        }
        
        $teacher->restore();

        return Redirect::back()->with('success', 'Teacher restored.');
    }

    public function getCitiesByRegion(string $region)
    {
        return response()->json([
            'cities' => Teacher::getCitiesByRegion($region)
        ]);
    }

    // Метод для отримання фото вчителя
    private function getTeacherPhoto(Teacher $teacher)
    {
        // Спробуємо знайти користувача з таким самим email
        $user = User::where('email', $teacher->email)->first();
        
        if ($user && $user->photo_path) {
            return URL::route('images.show', [
                'path' => $user->photo_path, 
                'w' => 40, 
                'h' => 40, 
                'fit' => 'crop'
            ]);
        }
        
        return null;
    }
} 