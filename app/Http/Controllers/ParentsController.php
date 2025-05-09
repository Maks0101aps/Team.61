<?php

namespace App\Http\Controllers;

use App\Models\ParentModel;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\URL;

class ParentsController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Parents/Index', [
            'filters' => Request::all('search', 'trashed'),
            'parents' => Auth::user()->account->parents()
                ->orderByName()
                ->filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($parent) => [
                    'id' => $parent->id,
                    'name' => $parent->name,
                    'phone' => $parent->phone,
                    'city' => $parent->city,
                    'parent_type' => $parent->parent_type,
                    'parent_type_name' => $parent->parent_type_name,
                    'deleted_at' => $parent->deleted_at,
                    'photo' => $this->getParentPhoto($parent),
                ]),
        ]);
    }

    public function create(): Response
    {
        $user = Auth::user();
        
        // Перевіряємо, чи є користувач учнем
        if ($user->isStudent()) {
            return Redirect::route('dashboard')->with('error', 'Учні не можуть створювати нових батьків.');
        }
        
        // Перевіряємо, чи є користувач батьком
        if ($user->isParent()) {
            return Redirect::route('dashboard')->with('error', 'Батьки не можуть створювати інших батьків.');
        }
        
        return Inertia::render('Parents/Create', [
            'students' => Student::all()->map(function($student) {
                return [
                    'id' => $student->id, 
                    'first_name' => $student->first_name, 
                    'middle_name' => $student->middle_name, 
                    'last_name' => $student->last_name, 
                    'full_name' => $student->first_name . ' ' . $student->middle_name . ' ' . $student->last_name
                ];
            }),
            'regions' => Teacher::getRegions(),
        ]);
    }

    public function store(): RedirectResponse
    {
        $user = Auth::user();
        
        // Перевіряємо, чи є користувач учнем
        if ($user->isStudent()) {
            return Redirect::route('dashboard')->with('error', 'Учні не можуть створювати нових батьків.');
        }
        
        // Перевіряємо, чи є користувач батьком
        if ($user->isParent()) {
            return Redirect::route('dashboard')->with('error', 'Батьки не можуть створювати інших батьків.');
        }
        
        $validated = Request::validate([
            'first_name' => ['required', 'max:50'],
            'middle_name' => ['required', 'max:50'],
            'last_name' => ['required', 'max:50'],
            'email' => ['nullable', 'max:50', 'email'],
            'parent_type' => ['required', 'in:mother,father'],
            'phone' => ['nullable', 'string', 'regex:/^\+380[0-9]{9}$/', 'max:13'],
            'address' => ['nullable', 'max:150'],
            'city' => ['nullable', 'max:50'],
            'region' => ['nullable', 'max:50'],
            'country' => ['nullable', 'max:2'],
            'postal_code' => ['nullable', 'max:25'],
            'children' => ['nullable', 'array'],
            'children.*.id' => ['exists:contacts,id'],
        ]);

        $childrenIds = collect($validated['children'] ?? [])->pluck('id')->filter();
        unset($validated['children']);

        $parent = Auth::user()->account->parents()->create($validated);

        if ($childrenIds->isNotEmpty()) {
            $parent->children()->attach($childrenIds);
        }

        return Redirect::route('parents.index')->with('success', 'Батьків створено.');
    }

    public function edit(ParentModel $parent): Response
    {
        $user = Auth::user();
        
        // Prevent students from editing parents
        if ($user->isStudent()) {
            return Redirect::route('parents.index')->with('error', 'Учні не можуть редагувати батьків.');
        }
        
        // If the user is a parent, they can only edit their own profile
        if ($user->isParent()) {
            $currentParent = ParentModel::where('email', $user->email)->first();
            if (!$currentParent || $currentParent->id !== $parent->id) {
                return Redirect::route('parents.index')->with('error', 'У вас немає доступу до редагування інших батьків.');
            }
        }
        
        $user = User::where('email', $parent->email)->first();
        $photoPath = $user && $user->photo_path ? $user->photo_path : null;
        
        return Inertia::render('Parents/Edit', [
            'parent' => [
                'id' => $parent->id,
                'first_name' => $parent->first_name,
                'middle_name' => $parent->middle_name,
                'last_name' => $parent->last_name,
                'email' => $parent->email,
                'phone' => $parent->phone,
                'parent_type' => $parent->parent_type,
                'address' => $parent->address,
                'city' => $parent->city,
                'region' => $parent->region,
                'country' => $parent->country,
                'postal_code' => $parent->postal_code,
                'children' => $parent->children->map->only('id'),
                'deleted_at' => $parent->deleted_at,
                'photo' => $this->getParentPhoto($parent),
                'photo_path' => $photoPath,
                'has_avatar' => (bool)$photoPath,
            ],
            'students' => Student::all()->map(function($student) {
                return [
                    'id' => $student->id, 
                    'first_name' => $student->first_name, 
                    'middle_name' => $student->middle_name, 
                    'last_name' => $student->last_name, 
                    'full_name' => $student->first_name . ' ' . $student->middle_name . ' ' . $student->last_name
                ];
            }),
            'regions' => Teacher::getRegions(),
        ]);
    }

    public function update(ParentModel $parent): RedirectResponse
    {
        $user = Auth::user();
        
        // Prevent students from updating parents
        if ($user->isStudent()) {
            return Redirect::route('parents.index')->with('error', 'Учні не можуть оновлювати батьків.');
        }
        
        // If the user is a parent, they can only update their own profile
        if ($user->isParent()) {
            $currentParent = ParentModel::where('email', $user->email)->first();
            if (!$currentParent || $currentParent->id !== $parent->id) {
                return Redirect::route('parents.index')->with('error', 'У вас немає доступу до оновлення інших батьків.');
            }
        }
        
        $validated = Request::validate([
            'first_name' => ['required', 'max:50'],
            'middle_name' => ['required', 'max:50'],
            'last_name' => ['required', 'max:50'],
            'email' => ['nullable', 'max:50', 'email'],
            'parent_type' => ['required', 'in:mother,father'],
            'phone' => ['nullable', 'string', 'regex:/^\+380[0-9]{9}$/', 'max:13'],
            'address' => ['nullable', 'max:150'],
            'city' => ['nullable', 'max:50'],
            'region' => ['nullable', 'max:50'],
            'country' => ['nullable', 'max:2'],
            'postal_code' => ['nullable', 'max:25'],
            'children' => ['nullable', 'array'],
            'children.*.id' => ['exists:contacts,id'],
            'avatar' => ['nullable', 'image', 'max:2048'],
        ]);

        $childrenIds = collect($validated['children'] ?? [])->pluck('id')->filter();
        unset($validated['children']);
        unset($validated['avatar']);

        if (isset($validated['phone'])) {
            $validated['phone'] = preg_replace('/[^0-9+]/', '', $validated['phone']);
        }

        $parent->update($validated);
        
        if (Request::file('avatar')) {
            $user = User::where('email', $parent->email)->first();
            
            if ($user) {
                if ($user->photo_path) {
                    $oldPath = storage_path('app/public/' . $user->photo_path);
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }
                
                $path = Request::file('avatar')->store('parents-avatars', 'public');
                $user->update(['photo_path' => $path]);
            }
        }
        
        $parent->children()->sync($childrenIds);

        return Redirect::back()->with('success', 'Батьків оновлено.');
    }

    public function destroy(ParentModel $parent): RedirectResponse
    {
        $user = Auth::user();
        
        // Prevent students from deleting parents
        if ($user->isStudent()) {
            return Redirect::route('parents.index')->with('error', 'Учні не можуть видаляти батьків.');
        }
        
        // If the user is a parent, they can only delete their own profile
        if ($user->isParent()) {
            $currentParent = ParentModel::where('email', $user->email)->first();
            if (!$currentParent || $currentParent->id !== $parent->id) {
                return Redirect::route('parents.index')->with('error', 'У вас немає доступу до видалення інших батьків.');
            }
        }
        
        $parent->delete();

        return Redirect::back()->with('success', 'Батьків видалено.');
    }

    public function restore(ParentModel $parent): RedirectResponse
    {
        $user = Auth::user();
        
        // Prevent students from restoring parents
        if ($user->isStudent()) {
            return Redirect::route('parents.index')->with('error', 'Учні не можуть відновлювати батьків.');
        }
        
        // If the user is a parent, they can only restore their own profile
        if ($user->isParent()) {
            $currentParent = ParentModel::where('email', $user->email)->first();
            if (!$currentParent || $currentParent->id !== $parent->id) {
                return Redirect::route('parents.index')->with('error', 'У вас немає доступу до відновлення інших батьків.');
            }
        }
        
        $parent->restore();

        return Redirect::back()->with('success', 'Батьків відновлено.');
    }

    /**
     * Get detailed parent information by ID.
     * This method is used by the registration form for students
     * to fetch their parent's address data.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getParentById($id)
    {
        $parent = ParentModel::find($id);
        
        if (!$parent) {
            return response()->json(['error' => 'Parent not found'], 404);
        }
        
        return response()->json([
            'parent' => [
                'id' => $parent->id,
                'first_name' => $parent->first_name,
                'middle_name' => $parent->middle_name,
                'last_name' => $parent->last_name,
                'email' => $parent->email,
                'phone' => $parent->phone,
                'address' => $parent->address,
                'city' => $parent->city,
                'region' => $parent->region,
                'street' => $parent->street,
                'house_number' => $parent->house_number,
                'postal_code' => $parent->postal_code,
            ]
        ]);
    }

    // Метод для отримання фото батька/матері
    private function getParentPhoto(ParentModel $parent)
    {
        $user = User::where('email', $parent->email)->first();
        
        if ($user && $user->photo_path) {
            if (request()->is('*/edit') || request()->is('*/create')) {
                return URL::route('images.show', [
                    'path' => $user->photo_path, 
                    'w' => 200, 
                    'h' => 200, 
                    'fit' => 'crop'
                ]);
            }
            
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
