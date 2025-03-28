<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\ParentModel;
use App\Models\Student;
use App\Models\User;
use App\Providers\AppServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Inertia\Response;

class RegisterController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        $parents = ParentModel::all();
        
        // Log the count of parents for debugging
        Log::info('Parents count: ' . $parents->count());
        if ($parents->count() > 0) {
            Log::info('First parent: ' . json_encode($parents->first()));
        }
        
        return Inertia::render('Auth/Register', [
            'roles' => [
                User::ROLE_TEACHER => 'Teacher',
                User::ROLE_STUDENT => 'Student',
                User::ROLE_PARENT => 'Parent',
            ],
            'parents' => $parents->map->only('id', 'first_name', 'middle_name', 'last_name'),
        ]);
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        $rules = [
            'first_name' => 'required|string|max:25',
            'last_name' => 'required|string|max:25',
            'middle_name' => 'nullable|string|max:25',
            'email' => 'required|string|email|max:50|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:' . implode(',', [
                User::ROLE_TEACHER,
                User::ROLE_STUDENT,
                User::ROLE_PARENT,
            ]),
        ];
        
        // Add parent_id validation only for student role
        if ($request->role === User::ROLE_STUDENT) {
            $rules['parent_id'] = 'required|exists:parent_models,id';
        }
        
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Find or create an account
        $account = Account::first() ?? Account::create(['name' => 'School Account']);

        // Create the user
        $user = User::create([
            'account_id' => $account->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'middle_name' => $request->middle_name ?? '',
            'email' => $request->email,
            'password' => $request->password,
            'role' => $request->role,
        ]);

        // If user is a student, create a student record and link it with the parent
        if ($request->role === User::ROLE_STUDENT && $request->has('parent_id')) {
            $student = Student::create([
                'account_id' => $account->id,
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name ?? '',
                'last_name' => $request->last_name,
                'email' => $request->email,
            ]);
            
            // Attach the student to the selected parent
            $parent = ParentModel::find($request->parent_id);
            if ($parent) {
                $parent->children()->attach($student->id);
            }
        }
        
        // If user is a parent, create a parent record
        if ($request->role === User::ROLE_PARENT) {
            $parent = ParentModel::create([
                'account_id' => $account->id,
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name ?? '',
                'last_name' => $request->last_name,
                'email' => $request->email,
            ]);
            
            // Log the created parent for debugging
            Log::info('Created parent: ' . json_encode($parent));
        }

        // Log the user in
        Auth::login($user);

        return redirect()->intended(AppServiceProvider::HOME);
    }
}
