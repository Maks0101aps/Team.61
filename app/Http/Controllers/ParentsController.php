<?php

namespace App\Http\Controllers;

use App\Models\ParentModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class ParentsController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Parents/Index', [
            'filters' => Request::all('search', 'trashed'),
            'parents' => Auth::user()->account->parents()
                ->with('organization')
                ->orderBy('name')
                ->filter(Request::only('search', 'trashed'))
                ->paginate(10)
                ->withQueryString()
                ->through(fn ($parent) => [
                    'id' => $parent->id,
                    'first_name' => $parent->first_name,
                    'middle_name' => $parent->middle_name,
                    'last_name' => $parent->last_name,
                    'phone' => $parent->phone,
                    'city' => $parent->city,
                    'deleted_at' => $parent->deleted_at,
                    'organization' => $parent->organization ? $parent->organization->only('name') : null,
                ]),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Parents/Create', [
            'organizations' => Auth::user()->account
                ->organizations()
                ->orderBy('name')
                ->get()
                ->map
                ->only('id', 'name'),
        ]);
    }

    public function store(): RedirectResponse
    {
        Auth::user()->account->parents()->create(
            Request::validate([
                'first_name' => ['required', 'max:50'],
                'middle_name' => ['required', 'max:50'],
                'last_name' => ['required', 'max:50'],
                'organization_id' => ['nullable', Rule::exists('organizations', 'id')->where(fn ($query) => $query->where('account_id', Auth::user()->account_id))],
                'email' => ['nullable', 'max:50', 'email'],
                'phone' => ['nullable', 'max:50'],
                'address' => ['nullable', 'max:150'],
                'city' => ['nullable', 'max:50'],
                'region' => ['nullable', 'max:50'],
                'country' => ['nullable', 'max:2'],
                'postal_code' => ['nullable', 'max:25'],
            ])
        );

        return Redirect::route('parents')->with('success', 'Parent created.');
    }

    public function edit(ParentModel $parent): Response
    {
        return Inertia::render('Parents/Edit', [
            'parent' => [
                'id' => $parent->id,
                'first_name' => $parent->first_name,
                'middle_name' => $parent->middle_name,
                'last_name' => $parent->last_name,
                'organization_id' => $parent->organization_id,
                'email' => $parent->email,
                'phone' => $parent->phone,
                'address' => $parent->address,
                'city' => $parent->city,
                'region' => $parent->region,
                'country' => $parent->country,
                'postal_code' => $parent->postal_code,
                'deleted_at' => $parent->deleted_at,
            ],
            'organizations' => Auth::user()->account->organizations()
                ->orderBy('name')
                ->get()
                ->map
                ->only('id', 'name'),
        ]);
    }

    public function update(ParentModel $parent): RedirectResponse
    {
        $parent->update(
            Request::validate([
                'first_name' => ['required', 'max:50'],
                'middle_name' => ['required', 'max:50'],
                'last_name' => ['required', 'max:50'],
                'organization_id' => ['nullable', Rule::exists('organizations', 'id')->where(fn ($query) => $query->where('account_id', Auth::user()->account_id))],
                'email' => ['nullable', 'max:50', 'email'],
                'phone' => ['nullable', 'max:50'],
                'address' => ['nullable', 'max:150'],
                'city' => ['nullable', 'max:50'],
                'region' => ['nullable', 'max:50'],
                'country' => ['nullable', 'max:2'],
                'postal_code' => ['nullable', 'max:25'],
            ])
        );

        return Redirect::back()->with('success', 'Parent updated.');
    }

    public function destroy(ParentModel $parent): RedirectResponse
    {
        $parent->delete();

        return Redirect::back()->with('success', 'Parent deleted.');
    }

    public function restore(ParentModel $parent): RedirectResponse
    {
        $parent->restore();

        return Redirect::back()->with('success', 'Parent restored.');
    }
}
