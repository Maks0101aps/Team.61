<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;
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
        return Inertia::render('Students/Index', [
            'filters' => Request::all('search', 'trashed'),
            'students' => Auth::user()->account->students()
                ->orderByName()
                ->filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($student) => [
                    'id' => $student->id,
                    'name' => $student->name,
                    'phone' => $student->phone,
                    'city' => $student->city,
                    'deleted_at' => $student->deleted_at,
                ]),
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
        Auth::user()->account->students()->create(
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

        return Redirect::route('students')->with('success', 'Student created.');
    }

    public function edit(Student $student): Response
    {
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
                'deleted_at' => $student->deleted_at,
            ],
            'regions' => Teacher::getRegions(),
        ]);
    }

    public function update(Student $student): RedirectResponse
    {
        $student->update(
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

        return Redirect::back()->with('success', 'Student updated.');
    }

    public function destroy(Student $student): RedirectResponse
    {
        $student->delete();

        return Redirect::back()->with('success', 'Student deleted.');
    }

    public function restore(Student $student): RedirectResponse
    {
        $student->restore();

        return Redirect::back()->with('success', 'Student restored.');
    }
} 