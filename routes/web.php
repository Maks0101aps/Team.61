<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\StudentsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImagesController;
use App\Http\Controllers\TeachersController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ParentsController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\TasksController;
use App\Http\Middleware\CheckStudentRole;
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
    ->middleware(['auth', CheckStudentRole::class]);

Route::put('users/{user}', [UsersController::class, 'update'])
    ->name('users.update')
    ->middleware(['auth', CheckStudentRole::class]);

Route::delete('users/{user}', [UsersController::class, 'destroy'])
    ->name('users.destroy')
    ->middleware(['auth', CheckStudentRole::class]);

Route::put('users/{user}/restore', [UsersController::class, 'restore'])
    ->name('users.restore')
    ->middleware(['auth', CheckStudentRole::class]);

// Parents
Route::get('parents', [ParentsController::class, 'index'])
    ->name('parents')
    ->middleware('auth');

Route::get('parents/create', [ParentsController::class, 'create'])
    ->name('parents.create')
    ->middleware(['auth', CheckStudentRole::class]);

Route::post('parents', [ParentsController::class, 'store'])
    ->name('parents.store')
    ->middleware(['auth', CheckStudentRole::class]);

Route::get('parents/{parent}/edit', [ParentsController::class, 'edit'])
    ->name('parents.edit')
    ->middleware(['auth', CheckStudentRole::class]);

Route::put('parents/{parent}', [ParentsController::class, 'update'])
    ->name('parents.update')
    ->middleware(['auth', CheckStudentRole::class]);

Route::delete('parents/{parent}', [ParentsController::class, 'destroy'])
    ->name('parents.destroy')
    ->middleware(['auth', CheckStudentRole::class]);

Route::get('parents/{parent}/restore', [ParentsController::class, 'restore'])
    ->name('parents.restore')
    ->middleware(['auth', CheckStudentRole::class]);

// Teachers

Route::get('teachers', [TeachersController::class, 'index'])
    ->name('teachers')
    ->middleware('auth');

Route::get('teachers/cities/{region}', [TeachersController::class, 'getCitiesByRegion'])
    ->name('teachers.cities')
    ->middleware('auth');

Route::get('teachers/create', [TeachersController::class, 'create'])
    ->name('teachers.create')
    ->middleware(['auth', CheckStudentRole::class]);

Route::post('teachers', [TeachersController::class, 'store'])
    ->name('teachers.store')
    ->middleware(['auth', CheckStudentRole::class]);

Route::get('teachers/{teacher}/edit', [TeachersController::class, 'edit'])
    ->name('teachers.edit')
    ->middleware(['auth', CheckStudentRole::class]);

Route::put('teachers/{teacher}', [TeachersController::class, 'update'])
    ->name('teachers.update')
    ->middleware(['auth', CheckStudentRole::class]);

Route::delete('teachers/{teacher}', [TeachersController::class, 'destroy'])
    ->name('teachers.destroy')
    ->middleware(['auth', CheckStudentRole::class]);

Route::put('teachers/{teacher}/restore', [TeachersController::class, 'restore'])
    ->name('teachers.restore')
    ->middleware(['auth', CheckStudentRole::class]);

// Images

Route::get('/img/{path}', [ImagesController::class, 'show'])
    ->where('path', '.*')
    ->name('image');

// Events
Route::middleware('auth')->group(function () {
    // Index route - all authenticated users can view events
    Route::get('events', [EventsController::class, 'index'])->name('events.index');
    
    // Routes that students should not access
    Route::middleware([CheckStudentRole::class])->group(function () {
        Route::get('events/create', [EventsController::class, 'create'])->name('events.create');
        Route::post('events', [EventsController::class, 'store'])->name('events.store');
        Route::get('events/{event}/edit', [EventsController::class, 'edit'])->name('events.edit');
        Route::put('events/{event}', [EventsController::class, 'update'])->name('events.update');
        Route::delete('events/{event}', [EventsController::class, 'destroy'])->name('events.destroy');
        Route::put('events/{event}/restore', [EventsController::class, 'restore'])->name('events.restore');
    });
});

// Tasks
Route::middleware('auth')->group(function () {
    // Index route - all authenticated users can view tasks
    Route::get('tasks', [TasksController::class, 'index'])->name('tasks.index');
    
    // Routes that students should not access
    Route::middleware([CheckStudentRole::class])->group(function () {
        Route::get('tasks/create', [TasksController::class, 'create'])->name('tasks.create');
        Route::post('tasks', [TasksController::class, 'store'])->name('tasks.store');
        Route::get('tasks/{task}/edit', [TasksController::class, 'edit'])->name('tasks.edit');
        Route::put('tasks/{task}', [TasksController::class, 'update'])->name('tasks.update');
        Route::delete('tasks/{task}', [TasksController::class, 'destroy'])->name('tasks.destroy');
        Route::put('tasks/{task}/restore', [TasksController::class, 'restore'])->name('tasks.restore');
    });
});

// Students

Route::get('students', [StudentsController::class, 'index'])
    ->name('students')
    ->middleware('auth');

Route::get('students/create', [StudentsController::class, 'create'])
    ->name('students.create')
    ->middleware(['auth', CheckStudentRole::class]);

Route::post('students', [StudentsController::class, 'store'])
    ->name('students.store')
    ->middleware(['auth', CheckStudentRole::class]);

Route::get('students/{student}/edit', [StudentsController::class, 'edit'])
    ->name('students.edit')
    ->middleware(['auth', CheckStudentRole::class]);

Route::put('students/{student}', [StudentsController::class, 'update'])
    ->name('students.update')
    ->middleware(['auth', CheckStudentRole::class]);

Route::delete('students/{student}', [StudentsController::class, 'destroy'])
    ->name('students.destroy')
    ->middleware(['auth', CheckStudentRole::class]);

Route::put('students/{student}/restore', [StudentsController::class, 'restore'])
    ->name('students.restore')
    ->middleware(['auth', CheckStudentRole::class]);

// Cities lookup - usable by any form requiring city selection
Route::get('cities/{region}', function ($region) {
    return response()->json([
        'cities' => \App\Models\Teacher::getCitiesByRegion($region)
    ]);
})->middleware('auth')->name('cities.by_region');


