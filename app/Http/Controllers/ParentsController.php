<?php

namespace App\Http\Controllers;

use App\Models\ParentModel;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use Inertia\Response;

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
                ]),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Parents/Create', [
            'students' => Student::all()->map->only('id', 'first_name', 'middle_name', 'last_name', 'full_name'),
            'regions' => Teacher::getRegions(),
        ]);
    }

    public function store(): RedirectResponse
    {
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
            ],
            'students' => Student::all()->map->only('id', 'first_name', 'middle_name', 'last_name', 'full_name'),
            'regions' => Teacher::getRegions(),
        ]);
    }

    public function update(ParentModel $parent): RedirectResponse
    {
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

        // Очищаем номер телефона от форматирования перед сохранением
        if (isset($validated['phone'])) {
            $validated['phone'] = preg_replace('/[^0-9+]/', '', $validated['phone']);
        }

        $parent->update($validated);
        
        // Sync the children relationships
        $parent->children()->sync($childrenIds);

        return Redirect::back()->with('success', 'Батьків оновлено.');
    }

    public function destroy(ParentModel $parent): RedirectResponse
    {
        $parent->delete();

        return Redirect::back()->with('success', 'Батьків видалено.');
    }

    public function restore(ParentModel $parent): RedirectResponse
    {
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
}
