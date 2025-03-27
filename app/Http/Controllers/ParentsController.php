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
        Auth::user()->account->parents()->create(
            Request::validate([
                'first_name' => ['required', 'max:50'],
                'middle_name' => ['required', 'max:50'],
                'last_name' => ['required', 'max:50'],
                'email' => ['nullable', 'max:50', 'email'],
                'phone' => ['nullable', 'max:50'],
                'address' => ['nullable', 'max:150'],
                'city' => ['nullable', 'max:50'],
                'region' => ['nullable', 'max:50'],
                'country' => ['nullable', 'max:2'],
                'postal_code' => ['nullable', 'max:25'],
            ])
        );

        return Redirect::route('parents')->with('success', 'Батька створено.');
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
        $parent->update(
            Request::validate([
                'first_name' => ['required', 'max:50'],
                'middle_name' => ['required', 'max:50'],
                'last_name' => ['required', 'max:50'],
                'email' => ['nullable', 'max:50', 'email'],
                'phone' => ['nullable', 'max:50'],
                'address' => ['nullable', 'max:150'],
                'city' => ['nullable', 'max:50'],
                'region' => ['nullable', 'max:50'],
                'country' => ['nullable', 'max:2'],
                'postal_code' => ['nullable', 'max:25'],
            ])
        );

        return Redirect::back()->with('success', 'Батька оновлено.');
    }

    public function destroy(ParentModel $parent): RedirectResponse
    {
        $parent->delete();

        return Redirect::back()->with('success', 'Батька видалено.');
    }

    public function restore(ParentModel $parent): RedirectResponse
    {
        $parent->restore();

        return Redirect::back()->with('success', 'Батька відновлено.');
    }
}
