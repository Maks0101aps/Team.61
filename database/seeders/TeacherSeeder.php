<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Organization;
use App\Models\Teacher;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $account = Account::first() ?? Account::create(['name' => 'School Account']);
        $organization = Organization::first() ?? Organization::create([
            'account_id' => $account->id,
            'name' => 'School 61',
            'email' => 'info@school61.example.com',
            'phone' => '380441234567',
        ]);

        $teacherData = [
            [
                'first_name' => 'Олена',
                'middle_name' => 'Петрівна',
                'last_name' => 'Коваленко',
                'email' => 'kovalenko@example.com',
                'phone' => '380501234567',
                'subject' => 'Математика',
            ],
            [
                'first_name' => 'Іван',
                'middle_name' => 'Андрійович',
                'last_name' => 'Шевченко',
                'email' => 'shevchenko@example.com',
                'phone' => '380671234567',
                'subject' => 'Фізика',
            ],
            [
                'first_name' => 'Марія',
                'middle_name' => 'Олександрівна',
                'last_name' => 'Бондаренко',
                'email' => 'bondarenko@example.com',
                'phone' => '380631234567',
                'subject' => 'Українська мова',
            ],
            [
                'first_name' => 'Василь',
                'middle_name' => 'Миколайович',
                'last_name' => 'Мельник',
                'email' => 'melnyk@example.com',
                'phone' => '380661234567',
                'subject' => 'Історія',
            ],
            [
                'first_name' => 'Наталія',
                'middle_name' => 'Ігорівна',
                'last_name' => 'Савченко',
                'email' => 'savchenko@example.com',
                'phone' => '380931234567',
                'subject' => 'Біологія',
            ],
        ];

        foreach ($teacherData as $data) {
            Teacher::create([
                'account_id' => $account->id,
                'organization_id' => $organization->id,
                'first_name' => $data['first_name'],
                'middle_name' => $data['middle_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'subject' => $data['subject'],
            ]);
        }
    }
}
