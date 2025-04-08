<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\ParentModel;
use App\Models\Student;
use App\Models\Teacher;
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
            'parentTypes' => ParentModel::getParentTypes(),
            'parents' => $parents->map->only('id', 'first_name', 'middle_name', 'last_name', 'phone', 'address', 'city', 'region', 'postal_code', 'street', 'house_number'),
            // Добавляем списки для полей формы учителя
            'subjects' => Teacher::getSubjects(),
            'qualifications' => Teacher::getQualifications(),
            'regions' => Teacher::getRegions(),
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
        
        // Add parent_type validation only for parent role
        if ($request->role === User::ROLE_PARENT) {
            $rules['parent_type'] = 'required|in:' . implode(',', [
                ParentModel::TYPE_MOTHER,
                ParentModel::TYPE_FATHER,
            ]);
        }
        
        // Add teacher fields validation only for teacher role
        if ($request->role === User::ROLE_TEACHER) {
            $rules['subject'] = 'required|string|max:100';
            $rules['qualification'] = 'required|string|max:100';
            $rules['phone'] = 'nullable|string|regex:/^\+380[0-9]{9}$/|max:13';
            $rules['region'] = 'nullable|string|max:50';
            $rules['city'] = 'nullable|string|max:50';
            $rules['postal_code'] = 'nullable|string|max:25';
        }
        
        // Add parent fields validation for parent role
        if ($request->role === User::ROLE_PARENT) {
            $rules['phone'] = 'nullable|string|regex:/^\+380[0-9]{9}$/|max:13';
            $rules['region'] = 'nullable|string|max:50';
            $rules['city'] = 'nullable|string|max:50';
            $rules['street'] = 'nullable|string|max:100';
            $rules['house_number'] = 'nullable|string|max:20';
            $rules['postal_code'] = 'nullable|string|max:25';
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
                'parent_type' => $request->parent_type,
                'phone' => $request->phone,
                'region' => $request->region,
                'city' => $request->city,
                'street' => $request->street,
                'house_number' => $request->house_number,
                'postal_code' => $request->postal_code,
            ]);
            
            // Log the created parent for debugging
            Log::info('Created parent: ' . json_encode($parent));
        }
        
        // If user is a teacher, create a teacher record
        if ($request->role === User::ROLE_TEACHER) {
            $teacher = Teacher::create([
                'account_id' => $account->id,
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name ?? '',
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone ?? '',
                'subject' => $request->subject,
                'qualification' => $request->qualification,
                'region' => $request->region ?? '',
                'city' => $request->city ?? '',
                'country' => 'UA', // Всегда Украина
                'postal_code' => $request->postal_code ?? '',
            ]);
            
            // Log the created teacher for debugging
            Log::info('Created teacher: ' . json_encode($teacher));
        }

        // Log the user in
        Auth::login($user);

        return redirect()->intended(AppServiceProvider::HOME);
    }
}
