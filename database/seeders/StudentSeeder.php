<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Organization;
use App\Models\Student;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
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
        
        $studentsData = [
            // Семья Петренко
            [
                'first_name' => 'Іван',
                'middle_name' => 'Петрович',
                'last_name' => 'Петренко',
                'email' => 'student.petrenko@example.com',
                'phone' => '380971111111',
                'address' => 'вул. Хрещатик, 1',
                'city' => 'Київ',
                'region' => 'Київська область',
                'country' => 'UA',
                'postal_code' => '01001',
                'class' => '7-А',
            ],
            [
                'first_name' => 'Анна',
                'middle_name' => 'Петрівна',
                'last_name' => 'Петренко',
                'email' => 'anna.petrenko@example.com',
                'phone' => '380972222222',
                'address' => 'вул. Хрещатик, 1',
                'city' => 'Київ',
                'region' => 'Київська область',
                'country' => 'UA',
                'postal_code' => '01001',
                'class' => '5-Б',
            ],
            // Семья Іваненко
            [
                'first_name' => 'Максим',
                'middle_name' => 'Андрійович',
                'last_name' => 'Іваненко',
                'email' => 'maksym.ivanenko@example.com',
                'phone' => '380973333333',
                'address' => 'вул. Володимирська, 10',
                'city' => 'Київ',
                'region' => 'Київська область',
                'country' => 'UA',
                'postal_code' => '01001',
                'class' => '9-В',
            ],
            [
                'first_name' => 'Софія',
                'middle_name' => 'Андріївна',
                'last_name' => 'Іваненко',
                'email' => 'sofia.ivanenko@example.com',
                'phone' => '380974444444',
                'address' => 'вул. Володимирська, 10',
                'city' => 'Київ',
                'region' => 'Київська область',
                'country' => 'UA',
                'postal_code' => '01001',
                'class' => '3-А',
            ],
            // Семья Коваленко
            [
                'first_name' => 'Олександр',
                'middle_name' => 'Сергійович',
                'last_name' => 'Коваленко',
                'email' => 'oleksandr.kovalenko@example.com',
                'phone' => '380975555555',
                'address' => 'вул. Шевченка, 15',
                'city' => 'Київ',
                'region' => 'Київська область',
                'country' => 'UA',
                'postal_code' => '01001',
                'class' => '11-А',
            ],
            [
                'first_name' => 'Марія',
                'middle_name' => 'Сергіївна',
                'last_name' => 'Коваленко',
                'email' => 'maria.kovalenko@example.com',
                'phone' => '380976666666',
                'address' => 'вул. Шевченка, 15',
                'city' => 'Київ',
                'region' => 'Київська область',
                'country' => 'UA',
                'postal_code' => '01001',
                'class' => '8-Б',
            ],
            // Семья Мельник
            [
                'first_name' => 'Артем',
                'middle_name' => 'Вікторович',
                'last_name' => 'Мельник',
                'email' => 'artem.melnyk@example.com',
                'phone' => '380977777777',
                'address' => 'вул. Франка, 22',
                'city' => 'Київ',
                'region' => 'Київська область',
                'country' => 'UA',
                'postal_code' => '01001',
                'class' => '6-А',
            ],
            [
                'first_name' => 'Дарина',
                'middle_name' => 'Вікторівна',
                'last_name' => 'Мельник',
                'email' => 'daryna.melnyk@example.com',
                'phone' => '380978888888',
                'address' => 'вул. Франка, 22',
                'city' => 'Київ',
                'region' => 'Київська область',
                'country' => 'UA',
                'postal_code' => '01001',
                'class' => '2-Б',
            ],
            // Семья Бондаренко
            [
                'first_name' => 'Денис',
                'middle_name' => 'Олегович',
                'last_name' => 'Бондаренко',
                'email' => 'denys.bondarenko@example.com',
                'phone' => '380979999999',
                'address' => 'вул. Лесі Українки, 5',
                'city' => 'Київ',
                'region' => 'Київська область',
                'country' => 'UA',
                'postal_code' => '01001',
                'class' => '10-А',
            ],
            [
                'first_name' => 'Оксана',
                'middle_name' => 'Олегівна',
                'last_name' => 'Бондаренко',
                'email' => 'oksana.bondarenko@example.com',
                'phone' => '380971234567',
                'address' => 'вул. Лесі Українки, 5',
                'city' => 'Київ',
                'region' => 'Київська область',
                'country' => 'UA',
                'postal_code' => '01001',
                'class' => '4-В',
            ],
            // Семья Шевченко
            [
                'first_name' => 'Богдан',
                'middle_name' => 'Михайлович',
                'last_name' => 'Шевченко',
                'email' => 'bohdan.shevchenko@example.com',
                'phone' => '380977654321',
                'address' => 'вул. Сагайдачного, 12',
                'city' => 'Київ',
                'region' => 'Київська область',
                'country' => 'UA',
                'postal_code' => '01001',
                'class' => '9-Б',
            ],
            [
                'first_name' => 'Юлія',
                'middle_name' => 'Михайлівна',
                'last_name' => 'Шевченко',
                'email' => 'yulia.shevchenko@example.com',
                'phone' => '380979876543',
                'address' => 'вул. Сагайдачного, 12',
                'city' => 'Київ',
                'region' => 'Київська область',
                'country' => 'UA',
                'postal_code' => '01001',
                'class' => '5-А',
            ],
            // Семья Савченко
            [
                'first_name' => 'Роман',
                'middle_name' => 'Григорійович',
                'last_name' => 'Савченко',
                'email' => 'roman.savchenko@example.com',
                'phone' => '380971122334',
                'address' => 'вул. Богдана Хмельницького, 30',
                'city' => 'Київ',
                'region' => 'Київська область',
                'country' => 'UA',
                'postal_code' => '01001',
                'class' => '11-Б',
            ],
            [
                'first_name' => 'Вікторія',
                'middle_name' => 'Григорівна',
                'last_name' => 'Савченко',
                'email' => 'viktoria.savchenko@example.com',
                'phone' => '380975544332',
                'address' => 'вул. Богдана Хмельницького, 30',
                'city' => 'Київ',
                'region' => 'Київська область',
                'country' => 'UA',
                'postal_code' => '01001',
                'class' => '7-Б',
            ],
        ];
        
        foreach ($studentsData as $data) {
            Student::create([
                'account_id' => $account->id,
                'first_name' => $data['first_name'],
                'middle_name' => $data['middle_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'address' => $data['address'],
                'city' => $data['city'],
                'region' => $data['region'],
                'country' => $data['country'],
                'postal_code' => $data['postal_code'],
                'class' => $data['class'],
            ]);
        }
        
        // Check if test student already exists
        $existingTestStudent = Student::where('email', 'jimmydoe@example.com')->first();
        
        if (!$existingTestStudent) {
            // Create a test student account
            Student::create([
                'account_id' => $account->id,
                'first_name' => 'Jimmy',
                'middle_name' => 'Test',
                'last_name' => 'Doe',
                'email' => 'jimmydoe@example.com',
                'phone' => '380501234567',
                'address' => 'Test Address',
                'city' => 'Kyiv',
                'region' => 'Kyiv Region',
                'country' => 'UA',
                'postal_code' => '01001',
                'class' => '8-A',
            ]);
            
            $this->command->info('Test student created successfully. Email: jimmydoe@example.com');
        } else {
            $this->command->info('Test student already exists. Skipping creation.');
        }
    }
}