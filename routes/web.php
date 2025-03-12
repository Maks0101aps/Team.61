<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ContactsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImagesController;
use App\Http\Controllers\TeachersController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ParentsController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\TasksController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Auth

Route::get('login', [AuthenticatedSessionController::class, 'create'])
    ->name('login')
    ->middleware('guest');

Route::post('login', [AuthenticatedSessionController::class, 'store'])
    ->name('login.store')
    ->middleware('guest');

Route::delete('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

// Registration
Route::get('register', [RegisterController::class, 'create'])
    ->name('register')
    ->middleware('guest');

Route::post('register', [RegisterController::class, 'store'])
    ->name('register.store')
    ->middleware('guest');

// Dashboard

Route::get('/', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->middleware('auth');

// Users

Route::get('users', [UsersController::class, 'index'])
    ->name('users')
    ->middleware('auth');

Route::middleware(['auth', 'App\Http\Middleware\CheckRole:teacher'])->group(function () {
    Route::get('users/create', [UsersController::class, 'create'])
        ->name('users.create');

    Route::post('users', [UsersController::class, 'store'])
        ->name('users.store');
});

Route::get('users/{user}/edit', [UsersController::class, 'edit'])
    ->name('users.edit')
    ->middleware('auth');

Route::put('users/{user}', [UsersController::class, 'update'])
    ->name('users.update')
    ->middleware('auth');

Route::delete('users/{user}', [UsersController::class, 'destroy'])
    ->name('users.destroy')
    ->middleware('auth');

Route::put('users/{user}/restore', [UsersController::class, 'restore'])
    ->name('users.restore')
    ->middleware('auth');

// Contacts

Route::get('contacts', [ContactsController::class, 'index'])
    ->name('contacts')
    ->middleware('auth');

Route::get('contacts/create', [ContactsController::class, 'create'])
    ->name('contacts.create')
    ->middleware('auth');

Route::post('contacts', [ContactsController::class, 'store'])
    ->name('contacts.store')
    ->middleware('auth');

Route::get('contacts/{contact}/edit', [ContactsController::class, 'edit'])
    ->name('contacts.edit')
    ->middleware('auth');

Route::put('contacts/{contact}', [ContactsController::class, 'update'])
    ->name('contacts.update')
    ->middleware('auth');

Route::delete('contacts/{contact}', [ContactsController::class, 'destroy'])
    ->name('contacts.destroy')
    ->middleware('auth');

Route::put('contacts/{contact}/restore', [ContactsController::class, 'restore'])
    ->name('contacts.restore')
    ->middleware('auth');

// Parents
Route::get('parents', [ParentsController::class, 'index'])
    ->name('parents')
    ->middleware('auth');

Route::get('parents/create', [ParentsController::class, 'create'])
    ->name('parents.create')
    ->middleware('auth');

Route::post('parents', [ParentsController::class, 'store'])
    ->name('parents.store')
    ->middleware('auth');

Route::get('parents/{parent}/edit', [ParentsController::class, 'edit'])
    ->name('parents.edit')
    ->middleware('auth');

Route::put('parents/{parent}', [ParentsController::class, 'update'])
    ->name('parents.update')
    ->middleware('auth');

Route::delete('parents/{parent}', [ParentsController::class, 'destroy'])
    ->name('parents.destroy')
    ->middleware('auth');

Route::get('parents/{parent}/restore', [ParentsController::class, 'restore'])
    ->name('parents.restore')
    ->middleware('auth');

// Teachers

Route::get('teachers', [TeachersController::class, 'index'])
    ->name('teachers')
    ->middleware('auth');

Route::get('teachers/create', [TeachersController::class, 'create'])
    ->name('teachers.create')
    ->middleware('auth');

Route::post('teachers', [TeachersController::class, 'store'])
    ->name('teachers.store')
    ->middleware('auth');

Route::get('teachers/{teacher}/edit', [TeachersController::class, 'edit'])
    ->name('teachers.edit')
    ->middleware('auth');

Route::put('teachers/{teacher}', [TeachersController::class, 'update'])
    ->name('teachers.update')
    ->middleware('auth');

Route::delete('teachers/{teacher}', [TeachersController::class, 'destroy'])
    ->name('teachers.destroy')
    ->middleware('auth');

Route::put('teachers/{teacher}/restore', [TeachersController::class, 'restore'])
    ->name('teachers.restore')
    ->middleware('auth');

// Images

Route::get('/img/{path}', [ImagesController::class, 'show'])
    ->where('path', '.*')
    ->name('image');

// Events
Route::middleware('auth')->group(function () {
    Route::resource('events', EventsController::class);
    Route::put('events/{event}/restore', [EventsController::class, 'restore'])
        ->name('events.restore');
});

// Tasks
Route::middleware('auth')->group(function () {
    Route::resource('tasks', TasksController::class);
    Route::put('tasks/{task}/restore', [TasksController::class, 'restore'])
        ->name('tasks.restore');
});


