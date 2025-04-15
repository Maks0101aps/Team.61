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
use App\Http\Controllers\ReportsController;
use App\Http\Middleware\CheckStudentRole;
use App\Http\Middleware\CheckParentRole;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CitiesController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\GoogleCalendarController;



Route::get('login', [AuthenticatedSessionController::class, 'create'])
    ->name('login')
    ->middleware('guest');

Route::post('login', [AuthenticatedSessionController::class, 'store'])
    ->name('login.store')
    ->middleware('guest');

Route::delete('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

Route::get('register', [RegisterController::class, 'create'])
    ->name('register')
    ->middleware('guest');

Route::post('register', [RegisterController::class, 'store'])
    ->name('register.store')
    ->middleware('guest');

Route::get('email/verify', [VerificationController::class, 'show'])
    ->name('verification.notice')
    ->middleware('auth');

Route::get('email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
    ->name('verification.verify')
    ->middleware(['auth', 'signed']);

Route::post('email/resend', [VerificationController::class, 'resend'])
    ->name('verification.resend')
    ->middleware(['auth', 'throttle:6,1']);

Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])
    ->name('password.request')
    ->middleware('guest');

Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])
    ->name('password.email')
    ->middleware('guest');

Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])
    ->name('password.reset')
    ->middleware('guest');

Route::post('password/reset', [ResetPasswordController::class, 'reset'])
    ->name('password.update')
    ->middleware('guest');


Route::get('/', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->middleware(['auth', 'verified']);


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/users', [UsersController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UsersController::class, 'create'])->name('users.create');
    Route::post('/users', [UsersController::class, 'store'])->name('users.store');
    Route::get('/users/{user}', [UsersController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [UsersController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UsersController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UsersController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/{user}/restore', [UsersController::class, 'restore'])->name('users.restore');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/parents', [ParentsController::class, 'index'])->name('parents.index');
    Route::get('/parents/create', [ParentsController::class, 'create'])->name('parents.create');
    Route::post('/parents', [ParentsController::class, 'store'])->name('parents.store');
    Route::get('/parents/{parent}', [ParentsController::class, 'show'])->name('parents.show');
    Route::get('/parents/{parent}/edit', [ParentsController::class, 'edit'])->name('parents.edit');
    Route::put('/parents/{parent}', [ParentsController::class, 'update'])->name('parents.update');
    Route::delete('/parents/{parent}', [ParentsController::class, 'destroy'])->name('parents.destroy');
    Route::post('/parents/{parent}/restore', [ParentsController::class, 'restore'])->name('parents.restore');
});


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/teachers', [TeachersController::class, 'index'])->name('teachers.index');
    Route::get('/teachers/create', [TeachersController::class, 'create'])->name('teachers.create');
    Route::get('/teachers/cities/{region}', [TeachersController::class, 'getCitiesByRegion'])->name('teachers.cities.by_region');
    Route::post('/teachers', [TeachersController::class, 'store'])->name('teachers.store');
    Route::get('/teachers/{teacher}', [TeachersController::class, 'show'])->name('teachers.show');
    Route::get('/teachers/{teacher}/edit', [TeachersController::class, 'edit'])->name('teachers.edit');
    Route::put('/teachers/{teacher}', [TeachersController::class, 'update'])->name('teachers.update');
    Route::delete('/teachers/{teacher}', [TeachersController::class, 'destroy'])->name('teachers.destroy');
    Route::post('/teachers/{teacher}/restore', [TeachersController::class, 'restore'])->name('teachers.restore');
});


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/images/{path}', [ImagesController::class, 'show'])->name('images.show')->where('path', '.*');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/events', [EventsController::class, 'index'])->name('events.index');
    Route::get('/events/create-permissions', [EventsController::class, 'checkCreatePermissions'])->name('events.create.permissions');
    Route::get('/events/create', [EventsController::class, 'create'])->name('events.create');
    Route::post('/events', [EventsController::class, 'store'])->name('events.store');
    Route::get('/events/{event}', [EventsController::class, 'show'])->name('events.show');
    Route::get('/events/{event}/edit', [EventsController::class, 'edit'])->name('events.edit');
    Route::put('/events/{event}', [EventsController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}', [EventsController::class, 'destroy'])->name('events.destroy');
    Route::post('/events/{event}/restore', [EventsController::class, 'restore'])->name('events.restore');
    Route::post('/events/{event}/attach', [EventsController::class, 'attach'])->name('events.attach');
    Route::delete('/events/{event}/detach/{attachment}', [EventsController::class, 'detach'])->name('events.detach');
    Route::get('/calendar', [EventsController::class, 'calendar'])->name('events.calendar');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/tasks', [TasksController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/create', [TasksController::class, 'create'])->name('tasks.create');
    Route::post('/tasks', [TasksController::class, 'store'])->name('tasks.store');
    Route::get('/tasks/{task}', [TasksController::class, 'show'])->name('tasks.show');
    Route::get('/tasks/{task}/edit', [TasksController::class, 'edit'])->name('tasks.edit');
    Route::put('/tasks/{task}', [TasksController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{task}', [TasksController::class, 'destroy'])->name('tasks.destroy');
    Route::post('/tasks/{task}/restore', [TasksController::class, 'restore'])->name('tasks.restore');
});


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/students', [StudentsController::class, 'index'])->name('students.index');
    Route::get('/students/create', [StudentsController::class, 'create'])->name('students.create');
    Route::post('/students', [StudentsController::class, 'store'])->name('students.store');
    Route::get('/students/{student}', [StudentsController::class, 'show'])->name('students.show');
    Route::get('/students/{student}/edit', [StudentsController::class, 'edit'])->name('students.edit');
    Route::put('/students/{student}', [StudentsController::class, 'update'])->name('students.update');
    Route::delete('/students/{student}', [StudentsController::class, 'destroy'])->name('students.destroy');
    Route::post('/students/{student}/restore', [StudentsController::class, 'restore'])->name('students.restore');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/cities', [CitiesController::class, 'index'])->name('cities.index');
    Route::get('/cities/{region}', [CitiesController::class, 'getCitiesByRegion'])->name('cities.by_region');
});

Route::middleware(['guest'])->group(function () {
    Route::get('/cities/public', [CitiesController::class, 'publicIndex'])->name('cities.public.index');
    Route::get('/public/cities/{region}', [CitiesController::class, 'getCitiesByRegion'])->name('cities.public.by_region');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/api/registration/helpers', [RegisterController::class, 'getHelpers'])->name('registration.helpers');
    Route::get('/api/registration/parent/{id}', [RegisterController::class, 'getParentInfo'])->name('registration.parent.info');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
    Route::get('/reports/students', [ReportsController::class, 'students'])->name('reports.students');
    Route::get('/reports/teachers', [ReportsController::class, 'teachers'])->name('reports.teachers');
    Route::get('/reports/events', [ReportsController::class, 'events'])->name('reports.events');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/calendar/settings', [GoogleCalendarController::class, 'settings'])->name('calendar.settings');
    Route::get('/google-calendar/redirect', [GoogleCalendarController::class, 'redirectToGoogle'])->name('google.calendar.redirect');
    Route::get('/google-calendar/callback', [GoogleCalendarController::class, 'handleGoogleCallback'])->name('google.calendar.callback');
    Route::post('/calendar/sync-to-google', [GoogleCalendarController::class, 'syncToGoogle'])->name('calendar.sync.to.google');
    Route::post('/calendar/sync-from-google', [GoogleCalendarController::class, 'syncFromGoogle'])->name('calendar.sync.from.google');
    Route::delete('/google-calendar/disconnect', [GoogleCalendarController::class, 'disconnect'])->name('google.calendar.disconnect');
});

// API Endpoint for events (temporary fix)
Route::get('/api/events/{event}', [EventsController::class, 'apiGetEvent'])->name('api.events.show')->middleware(['auth', 'verified']);


