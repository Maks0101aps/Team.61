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
                    'qualification' => $teacher->qualification,
                    'deleted_at' => $teacher->deleted_at,
                    'organization' => $teacher->organization ? $teacher->organization->only('name') : null,
                ]),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Teachers/Create', [
            'subjects' => Teacher::getSubjects(),
            'qualifications' => Teacher::getQualifications(),
            'regions' => Teacher::getRegions(),
        ]);
    }

    public function store(): RedirectResponse
    {
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
            'postal_code' => ['nullable', 'max:25'],
        ]);

        // Always set the country to UA
        $validatedData['country'] = 'UA';
        $validatedData['organization_id'] = null;

        Auth::user()->account->teachers()->create($validatedData);

        return Redirect::route('teachers.index')->with('success', 'Teacher created.');
    }

    public function edit(Teacher $teacher): Response
    {
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
            ],
            'subjects' => Teacher::getSubjects(),
            'qualifications' => Teacher::getQualifications(),
            'regions' => Teacher::getRegions(),
            'cities' => $cities,
        ]);
    }

    public function update(Teacher $teacher): RedirectResponse
    {
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
            'postal_code' => ['nullable', 'max:25'],
        ]);

        // Always set the country to UA
        $validatedData['country'] = 'UA';
        $validatedData['organization_id'] = null;

        // Очищаем номер телефона от форматирования перед сохранением
        if (isset($validatedData['phone'])) {
            $validatedData['phone'] = preg_replace('/[^0-9+]/', '', $validatedData['phone']);
        }

        $teacher->update($validatedData);

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

    public function getCitiesByRegion(string $region)
    {
        return response()->json([
            'cities' => Teacher::getCitiesByRegion($region)
        ]);
    }
} 