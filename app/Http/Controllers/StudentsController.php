<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\ParentModel;
use App\Models\User;
use App\Notifications\StudentCredentials;
use Illuminate\Support\Str;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class StudentsController extends Controller
{
    public function index(): Response
    {
        $user = Auth::user();
        $students = [];
        
        if ($user->role === User::ROLE_PARENT) {
            // For parents, show all students but mark which ones are their children
            $parent = ParentModel::where('email', $user->email)->first();
            
            if ($parent) {
                // Get IDs of the parent's children
                $childrenIds = $parent->children()->pluck('student_id')->toArray();
                
                // Get all students
                $students = Auth::user()->account->students()
                    ->orderByName()
                    ->filter(Request::only('search', 'trashed'))
                    ->paginate(10)
                    ->withQueryString()
                    ->through(fn ($student) => [
                        'id' => $student->id,
                        'name' => $student->name,
                        'phone' => $student->phone,
                        'city' => $student->city,
                        'class' => $student->class,
                        'deleted_at' => $student->deleted_at,
                        'photo' => $this->getStudentPhoto($student),
                        'is_own_child' => in_array($student->id, $childrenIds), // Mark if this is parent's child
                    ]);
            }
        } else {
            // For teachers/admins, show all students
            $students = Auth::user()->account->students()
                ->orderByName()
                ->filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($student) => [
                    'id' => $student->id,
                    'name' => $student->name,
                    'phone' => $student->phone,
                    'city' => $student->city,
                    'class' => $student->class,
                    'deleted_at' => $student->deleted_at,
                    'photo' => $this->getStudentPhoto($student),
                ]);
        }
        
        return Inertia::render('Students/Index', [
            'filters' => Request::all('search', 'trashed'),
            'students' => $students,
            'userRole' => $user->role,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Students/Create', [
            'regions' => Teacher::getRegions(),
        ]);
    }

    public function store(): RedirectResponse
    {
        $user = Auth::user();
        
        // Validate input
        $validatedData = Request::validate([
            'first_name' => ['required', 'max:50'],
            'middle_name' => ['required', 'max:50'],
            'last_name' => ['required', 'max:50'],
            'email' => ['required', 'max:50', 'email', 'unique:users,email'],
            'phone' => ['nullable', 'regex:/^\+380\d{9}$/', 'max:13'],
            'address' => ['nullable', 'max:150'],
            'city' => ['nullable', 'max:50'],
            'region' => ['nullable', 'max:50'],
            'country' => ['nullable', 'max:2'],
            /*'postal_code' => ['nullable', 'max:25'],*/
            'class' => ['nullable', 'max:10'],
        ]);
        
        // Create the student
        $student = Auth::user()->account->students()->create($validatedData);
        
        // Generate a random password
        $password = Str::random(10);
        
        // Create a user account for the student
        $userAccount = User::create([
            'account_id' => Auth::user()->account_id,
            'first_name' => $validatedData['first_name'],
            'middle_name' => $validatedData['middle_name'],
            'last_name' => $validatedData['last_name'],
            'email' => $validatedData['email'],
            'password' => $password,
            'role' => User::ROLE_STUDENT,
            'email_verified_at' => now(), // Auto-verify student email
            'password_change_required' => true, // Требуется смена пароля при первом входе
        ]);
        
        // If the user is a parent, attach this student to them
        if ($user->role === User::ROLE_PARENT) {
            $parent = ParentModel::where('email', $user->email)->first();
            if ($parent) {
                $parent->children()->attach($student->id);
            }
        }
        
        // Send login credentials to the student's email
        $studentName = $validatedData['first_name'] . ' ' . $validatedData['last_name'];
        $userAccount->notify(new StudentCredentials($password, $studentName));

        return Redirect::route('students.index')->with('success', 'Учня успішно створено. Дані для входу відправлено на email.');
    }

    public function edit(Student $student): Response
    {
        $user = Auth::user();
        
        // If user is a parent, check if they have access to this student
        if ($user->role === User::ROLE_PARENT) {
            $parent = ParentModel::where('email', $user->email)->first();
            $hasAccess = $parent && $parent->children()->where('student_id', $student->id)->exists();
            
            if (!$hasAccess) {
                return Redirect::route('students.index')->with('error', 'У вас немає доступу до цього учня.');
            }
        }
        
        // If user is a student, they can only edit their own profile
        if ($user->role === User::ROLE_STUDENT) {
            $hasAccess = $student->email === $user->email;
            
            if (!$hasAccess) {
                return Redirect::route('students.index')->with('error', 'Учні можуть редагувати тільки свій профіль.');
            }
        }
        
        return Inertia::render('Students/Edit', [
            'student' => [
                'id' => $student->id,
                'first_name' => $student->first_name,
                'middle_name' => $student->middle_name,
                'last_name' => $student->last_name,
                'email' => $student->email,
                'phone' => $student->phone,
                'address' => $student->address,
                'city' => $student->city,
                'region' => $student->region,
                'postal_code' => $student->postal_code,
                'class' => $student->class,
                'deleted_at' => $student->deleted_at,
                'photo' => $this->getStudentPhoto($student),
            ],
            'regions' => Teacher::getRegions(),
        ]);
    }

    public function update(Student $student): RedirectResponse
    {
        $user = Auth::user();
        
        // If user is a parent, check if they have access to this student
        if ($user->role === User::ROLE_PARENT) {
            $parent = ParentModel::where('email', $user->email)->first();
            $hasAccess = $parent && $parent->children()->where('student_id', $student->id)->exists();
            
            if (!$hasAccess) {
                return Redirect::route('students.index')->with('error', 'У вас немає доступу до цього учня.');
            }
        }
        
        // If user is a student, they can only update their own profile
        if ($user->role === User::ROLE_STUDENT) {
            $hasAccess = $student->email === $user->email;
            
            if (!$hasAccess) {
                return Redirect::route('students.index')->with('error', 'Учні можуть оновлювати тільки свій профіль.');
            }
        }
        
        try {
            // Debug information to see what's being received
            \Log::info('Student update request data:', [
                'request_all' => Request::all(),
                'student_before' => $student->toArray()
            ]);
            
            // Validate input data
            $validatedData = Request::validate([
                'first_name' => ['required', 'max:50'],
                'middle_name' => ['required', 'max:50'],
                'last_name' => ['required', 'max:50'],
                'email' => ['nullable', 'max:50', 'email'],
                'phone' => ['nullable', 'regex:/^\+?380\d{9}$/', 'max:13'], // Made + optional
                'address' => ['nullable', 'max:150'],
                'city' => ['nullable', 'max:50'],
                'region' => ['nullable', 'max:50'],
                'country' => ['nullable', 'max:2'],
                'postal_code' => ['nullable', 'max:25'],
                'class' => ['nullable', 'max:10'],
                'avatar' => ['nullable', 'image', 'max:2048'],
            ]);

            // Debug the validated data to see what's being passed to update
            \Log::info('Validated data for student update:', $validatedData);

            // Format phone with + if missing
            if (!empty($validatedData['phone']) && strpos($validatedData['phone'], '+') !== 0) {
                $validatedData['phone'] = '+' . $validatedData['phone'];
            }

            // Remove avatar from validated data before updating student
            if (isset($validatedData['avatar'])) {
                unset($validatedData['avatar']);
            }
            
            // Force update with explicit field assignments to ensure changes are saved
            $student->first_name = $validatedData['first_name'];
            $student->middle_name = $validatedData['middle_name'];
            $student->last_name = $validatedData['last_name'];
            $student->email = $validatedData['email'] ?? $student->email;
            $student->phone = $validatedData['phone'] ?? $student->phone;
            $student->address = $validatedData['address'] ?? $student->address;
            $student->city = $validatedData['city'] ?? $student->city;
            $student->region = $validatedData['region'] ?? $student->region;
            $student->country = $validatedData['country'] ?? $student->country;
            $student->postal_code = $validatedData['postal_code'] ?? $student->postal_code;
            $student->class = $validatedData['class'] ?? $student->class;
            $student->save();
            
            // Debug student data after update to verify changes
            \Log::info('Student after update:', [
                'student_after' => $student->fresh()->toArray()
            ]);
            
            // Обработка загрузки аватарки
            if (Request::hasFile('avatar')) {
                // Найдем пользователя с таким же email для хранения аватарки
                $user = User::where('email', $student->email)->first();
                
                if ($user) {
                    // Удаляем старое фото, если оно существует
                    if ($user->photo_path) {
                        $oldPath = storage_path('app/public/' . $user->photo_path);
                        if (file_exists($oldPath)) {
                            unlink($oldPath);
                        }
                    }
                    
                    // Сохраняем новое фото
                    $path = Request::file('avatar')->store('students-avatars', 'public');
                    $user->update(['photo_path' => $path]);
                }
            }

            // Also update the corresponding user if email is the same
            if ($student->email) {
                $userToUpdate = User::where('email', $student->email)->first();
                if ($userToUpdate) {
                    $userToUpdate->first_name = $student->first_name;
                    $userToUpdate->middle_name = $student->middle_name;
                    $userToUpdate->last_name = $student->last_name;
                    $userToUpdate->save();
                }
            }
            
            return Redirect::back()->with('success', 'Інформацію про учня оновлено.');
            
        } catch (\Exception $e) {
            // Log any exceptions that occur during the update process
            \Log::error('Error updating student:', ['exception' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return Redirect::back()->with('error', 'Помилка при оновленні даних: ' . $e->getMessage());
        }
    }

    public function destroy(Student $student): RedirectResponse
    {
        $user = Auth::user();
        
        // If user is a parent, check if they have access to this student
        if ($user->role === User::ROLE_PARENT) {
            $parent = ParentModel::where('email', $user->email)->first();
            $hasAccess = $parent && $parent->children()->where('student_id', $student->id)->exists();
            
            if (!$hasAccess) {
                return Redirect::route('students.index')->with('error', 'У вас немає доступу до цього учня.');
            }
        }
        
        // If user is a student, they can only delete their own profile
        if ($user->role === User::ROLE_STUDENT) {
            $hasAccess = $student->email === $user->email;
            
            if (!$hasAccess) {
                return Redirect::route('students.index')->with('error', 'Учні можуть видаляти тільки свій профіль.');
            }
        }
        
        $student->delete();

        return Redirect::back()->with('success', 'Учня видалено.');
    }

    public function restore(Student $student): RedirectResponse
    {
        $user = Auth::user();
        
        // If user is a parent, check if they have access to this student
        if ($user->role === User::ROLE_PARENT) {
            $parent = ParentModel::where('email', $user->email)->first();
            $hasAccess = $parent && $parent->children()->where('student_id', $student->id)->exists();
            
            if (!$hasAccess) {
                return Redirect::route('students.index')->with('error', 'У вас немає доступу до цього учня.');
            }
        }
        
        // If user is a student, they can only restore their own profile
        if ($user->role === User::ROLE_STUDENT) {
            $hasAccess = $student->email === $user->email;
            
            if (!$hasAccess) {
                return Redirect::route('students.index')->with('error', 'Учні можуть відновлювати тільки свій профіль.');
            }
        }
        
        $student->restore();

        return Redirect::back()->with('success', 'Учня відновлено.');
    }

    // Метод для отримання фото студента
    private function getStudentPhoto(Student $student)
    {
        // Спробуємо знайти користувача з таким самим email
        $user = User::where('email', $student->email)->first();
        
        if ($user && $user->photo_path) {
            // Return the storage URL directly
            return '/storage/' . $user->photo_path;
        }
        
        return null;
    }
} 