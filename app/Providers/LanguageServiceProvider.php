<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

class LanguageServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Inertia::share('translations', function () {
            return $this->getTranslations();
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
            ],
        ];
    }
} 