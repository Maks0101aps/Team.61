<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * Register any application services.
     */
    public function register(): void
    {
        Model::unguard();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share translations with all Inertia responses
        Inertia::share('translations', function () {
            return $this->getTranslations();
        });
        
        // Share user role and ID as meta tags in the head
        Inertia::share('auth.user_meta', function () {
            if (Auth::check()) {
                return [
                    'role' => Auth::user()->role,
                    'id' => Auth::user()->id,
                ];
            }
            return null;
        });

        $this->bootRoute();
    }

    public function bootRoute(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
    
    /**
     * Get translations for common UI elements.
     *
     * @return array
     */
    private function getTranslations()
    {
        return [
            'uk' => [
                'students' => 'Студенти',
                'teachers' => 'Вчителі',
                'parents' => 'Батьки',
                'events' => 'Події',
                'tasks' => 'Завдання',
                'home' => 'Головна Сторінка',
                'profile' => 'Мій профіль',
                'users' => 'Користувачі',
                'logout' => 'Вийти',
                'create' => 'Створити',
                'edit' => 'Редагувати',
                'delete' => 'Видалити',
                'restore' => 'Відновити',
                'search' => 'Пошук',
                'filter' => 'Фільтр',
                'actions' => 'Дії',
                'name' => 'Ім\'я',
                'email' => 'Електронна пошта',
                'phone' => 'Телефон',
                'address' => 'Адреса',
                'city' => 'Місто',
                'save' => 'Зберегти',
                'cancel' => 'Скасувати',
                'first_name' => 'Ім\'я',
                'last_name' => 'Прізвище',
                'middle_name' => 'По батькові',
                'school_calendar' => 'Шкільний календар',
                'one_error' => 'Є одна помилка у формі.',
                'multiple_errors' => 'Є :count помилок у формі.',
                // Permissions errors
                'parents_cannot_edit_events' => 'Батьки не можуть редагувати події',
                'parents_cannot_delete_events' => 'Батьки не можуть видаляти події',
                'parents_cannot_create_events' => 'Батьки не можуть створювати події',
                'students_can_only_edit_own_events' => 'Учні можуть редагувати тільки власні події',
                'students_can_only_delete_own_events' => 'Учні можуть видаляти тільки власні події',
                'parents_cannot_create_tasks' => 'Батьки не можуть створювати завдання',
                'parents_cannot_edit_tasks' => 'Батьки не можуть редагувати завдання',
                'parents_cannot_delete_tasks' => 'Батьки не можуть видаляти завдання',
                'parents_cannot_restore_tasks' => 'Батьки не можуть відновлювати завдання',
                'students_cannot_create_tasks' => 'Учні не можуть створювати завдання',
                'students_can_only_edit_own_tasks' => 'Учні можуть редагувати тільки власні завдання',
                'students_can_only_delete_own_tasks' => 'Учні можуть видаляти тільки власні завдання',
                'students_can_only_restore_own_tasks' => 'Учні можуть відновлювати тільки власні завдання',
                'cannot_delete_attachments' => 'Ви не можете видаляти вкладення цієї події',
            ],
            'en' => [
                'students' => 'Students',
                'teachers' => 'Teachers',
                'parents' => 'Parents',
                'events' => 'Events',
                'tasks' => 'Tasks',
                'home' => 'Home Page',
                'profile' => 'My Profile',
                'users' => 'Users',
                'logout' => 'Logout',
                'create' => 'Create',
                'edit' => 'Edit',
                'delete' => 'Delete',
                'restore' => 'Restore',
                'search' => 'Search',
                'filter' => 'Filter',
                'actions' => 'Actions',
                'name' => 'Name',
                'email' => 'Email',
                'phone' => 'Phone',
                'address' => 'Address',
                'city' => 'City',
                'save' => 'Save',
                'cancel' => 'Cancel',
                'first_name' => 'First Name',
                'last_name' => 'Last Name',
                'middle_name' => 'Middle Name',
                'school_calendar' => 'School Calendar',
                'one_error' => 'There is one error in the form.',
                'multiple_errors' => 'There are :count errors in the form.',
                // Permissions errors
                'parents_cannot_edit_events' => 'Parents cannot edit events',
                'parents_cannot_delete_events' => 'Parents cannot delete events',
                'parents_cannot_create_events' => 'Parents cannot create events',
                'students_can_only_edit_own_events' => 'Students can only edit their own events',
                'students_can_only_delete_own_events' => 'Students can only delete their own events',
                'parents_cannot_create_tasks' => 'Parents cannot create tasks',
                'parents_cannot_edit_tasks' => 'Parents cannot edit tasks',
                'parents_cannot_delete_tasks' => 'Parents cannot delete tasks',
                'parents_cannot_restore_tasks' => 'Parents cannot restore tasks',
                'students_cannot_create_tasks' => 'Students cannot create tasks',
                'students_can_only_edit_own_tasks' => 'Students can only edit their own tasks',
                'students_can_only_delete_own_tasks' => 'Students can only delete their own tasks',
                'students_can_only_restore_own_tasks' => 'Students can only restore their own tasks',
                'cannot_delete_attachments' => 'You cannot delete attachments of this event',
            ],
        ];
    }
}
