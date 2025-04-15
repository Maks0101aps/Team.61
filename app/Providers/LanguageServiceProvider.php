<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

class LanguageServiceProvider extends ServiceProvider
{
    public function register()
    {
    }

    public function boot()
    {
        Inertia::share('translations', function () {
            return $this->getTranslations();
        });
    }
    
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
                'Event created.' => 'Подію створено.',
                'Event updated.' => 'Подію оновлено.',
                'Event deleted.' => 'Подію видалено.',
                'Event restored.' => 'Подію відновлено.',
                'Батьки не можуть створювати або змінювати події' => 'Батьки не можуть створювати або змінювати події',
                'Учні можуть створювати тільки особисті події' => 'Учні можуть створювати тільки особисті події',
                'Учні не можуть запрошувати вчителів на події' => 'Учні не можуть запрошувати вчителів на події',
                'Учні не можуть запрошувати батьків на події' => 'Учні не можуть запрошувати батьків на події',
                'Учні можуть редагувати тільки власні події' => 'Учні можуть редагувати тільки власні події',
                'Учні можуть видаляти тільки власні події' => 'Учні можуть видаляти тільки власні події',
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
                'Event created.' => 'Event created.',
                'Event updated.' => 'Event updated.',
                'Event deleted.' => 'Event deleted.',
                'Event restored.' => 'Event restored.',
                'Батьки не можуть створювати або змінювати події' => 'Parents cannot create or modify events',
                'Учні можуть створювати тільки особисті події' => 'Students can only create personal events',
                'Учні не можуть запрошувати вчителів на події' => 'Students cannot invite teachers to events',
                'Учні не можуть запрошувати батьків на події' => 'Students cannot invite parents to events',
                'Учні можуть редагувати тільки власні події' => 'Students can only edit their own events',
                'Учні можуть видаляти тільки власні події' => 'Students can only delete their own events',
            ],
        ];
    }
} 