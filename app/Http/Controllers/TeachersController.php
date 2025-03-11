<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
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
                    'deleted_at' => $teacher->deleted_at,
                    'organization' => $teacher->organization ? $teacher->organization->only('name') : null,
                ]),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Teachers/Create');
    }

    public function store(): RedirectResponse
    {
        Auth::user()->account->teachers()->create(
            Request::validate([
                'first_name' => ['required', 'max:50'],
                'middle_name' => ['required', 'max:50'],
                'last_name' => ['required', 'max:50'],
                'organization_id' => ['nullable', Rule::exists('organizations', 'id')->where(function ($query) {
                    $query->where('account_id', Auth::user()->account_id);
                })],
                'email' => ['nullable', 'max:50', 'email'],
                'phone' => ['nullable', 'max:50'],
                'subject' => ['nullable', 'max:100'],
                'qualification' => ['nullable', 'max:100'],
                'address' => ['nullable', 'max:150'],
                'city' => ['nullable', 'max:50'],
                'region' => ['nullable', 'max:50'],
                'country' => ['nullable', 'max:2'],
                'postal_code' => ['nullable', 'max:25'],
            ])
        );

        return Redirect::route('teachers')->with('success', 'Teacher created.');
    }

    public function edit(Teacher $teacher): Response
    {
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
            ],
            'organizations' => Auth::user()->account->organizations()
                ->orderBy('name')
                ->get()
                ->map
                ->only('id', 'name'),
        ]);
    }

    public function update(Teacher $teacher): RedirectResponse
    {
        $teacher->update(
            Request::validate([
                'first_name' => ['required', 'max:50'],
                'middle_name' => ['required', 'max:50'],
                'last_name' => ['required', 'max:50'],
                'organization_id' => [
                    'nullable',
                    Rule::exists('organizations', 'id')->where(fn ($query) => $query->where('account_id', Auth::user()->account_id)),
                ],
                'email' => ['nullable', 'max:50', 'email'],
                'phone' => ['nullable', 'max:50'],
                'subject' => ['nullable', 'max:100'],
                'qualification' => ['nullable', 'max:100'],
                'address' => ['nullable', 'max:150'],
                'city' => ['nullable', 'max:50'],
                'region' => ['nullable', 'max:50'],
                'country' => ['nullable', 'max:2'],
                'postal_code' => ['nullable', 'max:25'],
            ])
        );

        return Redirect::back()->with('success', 'Teacher updated.');
    }

    public function destroy(Teacher $teacher): RedirectResponse
    {
        $teacher->delete();

        return Redirect::back()->with('success', 'Teacher deleted.');
    }

    public function restore(Teacher $teacher): RedirectResponse
    {
        $teacher->restore();

        return Redirect::back()->with('success', 'Teacher restored.');
    }
} 