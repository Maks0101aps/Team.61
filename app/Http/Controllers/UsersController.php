<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ParentModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class UsersController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Users/Index', [
            'filters' => Request::all('search', 'role', 'trashed'),
            'users' => Auth::user()->account->users()
                ->orderByName()
                ->filter(Request::only('search', 'role', 'trashed'))
                ->get()
                ->transform(fn ($user) => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'owner' => $user->owner,
                    'photo' => $user->photo_path ? URL::route('image', ['path' => $user->photo_path, 'w' => 40, 'h' => 40, 'fit' => 'crop']) : null,
                    'deleted_at' => $user->deleted_at,
                ]),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Users/Create');
    }

    public function store(): RedirectResponse
    {
        Request::validate([
            'first_name' => ['required', 'max:50'],
            'last_name' => ['required', 'max:50'],
            'email' => ['required', 'max:50', 'email', Rule::unique('users')],
            'password' => ['nullable'],
            'owner' => ['required', 'boolean'],
            'photo' => ['nullable', 'image'],
        ]);

        Auth::user()->account->users()->create([
            'first_name' => Request::get('first_name'),
            'last_name' => Request::get('last_name'),
            'email' => Request::get('email'),
            'password' => Request::get('password'),
            'owner' => Request::get('owner'),
            'photo_path' => Request::file('photo') ? Request::file('photo')->store('users') : null,
        ]);

        return Redirect::route('users')->with('success', 'User created.');
    }

    public function edit(User $user): Response
    {
        if (Auth::id() !== $user->id && Auth::user()->role !== User::ROLE_TEACHER) {
            return Redirect::route('dashboard')->with('error', 'Ви можете редагувати тільки власний профіль');
        }
        
        $userData = [
            'id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'owner' => $user->owner,
            'photo' => $user->photo_path ? URL::route('image', ['path' => $user->photo_path, 'w' => 60, 'h' => 60, 'fit' => 'crop']) : null,
            'deleted_at' => $user->deleted_at,
            'role' => $user->role,
        ];
        
        // If the user is a parent, add parent-specific data
        if ($user->isParent()) {
            // Get parent data
            $parent = ParentModel::where('email', $user->email)->first();
            
            if ($parent) {
                $userData['middle_name'] = $parent->middle_name;
                $userData['city'] = $parent->city;
                $userData['region'] = $parent->region;
                $userData['country'] = $parent->country;
                $userData['postal_code'] = $parent->postal_code;
                $userData['street'] = $parent->street;
                $userData['house_number'] = $parent->house_number;
                $userData['phone'] = $parent->phone;
                
                // Explicitly join the parent-student table and load all student fields
                $children = DB::table('parent_student')
                    ->join('contacts', 'parent_student.student_id', '=', 'contacts.id')
                    ->where('parent_student.parent_model_id', $parent->id)
                    ->select('contacts.*')
                    ->get();

                $userData['children'] = $children->map(function($child) {
                    return [
                        'id' => $child->id,
                        'name' => $child->first_name . ' ' . $child->last_name,
                        'first_name' => $child->first_name,
                        'last_name' => $child->last_name,
                        'middle_name' => $child->middle_name,
                        'class' => $child->organization_id ?? null,
                        'email' => $child->email,
                    ];
                })->toArray();
            }
        }

        return Inertia::render('Users/Edit', [
            'user' => $userData
        ]);
    }

    public function update(User $user): RedirectResponse
    {
        if (Auth::id() !== $user->id && Auth::user()->role !== User::ROLE_TEACHER) {
            return Redirect::back()->with('error', 'Ви можете редагувати тільки власний профіль');
        }

        if (App::environment('demo') && $user->isDemoUser()) {
            return Redirect::back()->with('error', 'Updating the demo user is not allowed.');
        }

        // Validate core user fields
        $validated = Request::validate([
            'first_name' => ['required', 'max:50'],
            'last_name' => ['required', 'max:50'],
            'email' => ['required', 'max:50', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable'],
            'photo' => ['nullable', 'image'],
        ]);

        // For parents, validate additional fields
        if ($user->isParent()) {
            $parentData = Request::validate([
                'city' => ['nullable', 'string', 'max:100'],
                'region' => ['nullable', 'string', 'max:100'],
                'country' => ['nullable', 'string', 'max:100'],
                'postal_code' => ['nullable', 'string', 'max:20'],
                'street' => ['nullable', 'string', 'max:255'],
                'house_number' => ['nullable', 'string', 'max:50'],
                'phone' => ['nullable', 'string', 'regex:/^\+380[0-9]{9}$/', 'max:13'],
            ]);
            
            // Find and update the parent model
            $parent = ParentModel::where('email', $user->email)->first();
            if ($parent) {
                // Очищаем номер телефона от форматирования перед сохранением
                if (isset($parentData['phone'])) {
                    $parentData['phone'] = preg_replace('/[^0-9+]/', '', $parentData['phone']);
                }
                $parent->update($parentData);
            }
        }

        // Update user fields (except owner for parents)
        if ($user->isParent()) {
            $user->update([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
            ]);
        } else {
            $user->update([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'owner' => Request::get('owner', false),
            ]);
        }

        if (Request::file('photo')) {
            $user->update(['photo_path' => Request::file('photo')->store('users')]);
        }

        if (Request::get('password')) {
            $user->update(['password' => Request::get('password')]);
        }

        return Redirect::back()->with('success', 'User updated.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if (App::environment('demo') && $user->isDemoUser()) {
            return Redirect::back()->with('error', 'Deleting the demo user is not allowed.');
        }

        $user->delete();

        return Redirect::back()->with('success', 'User deleted.');
    }

    public function restore(User $user): RedirectResponse
    {
        $user->restore();

        return Redirect::back()->with('success', 'User restored.');
    }
}
