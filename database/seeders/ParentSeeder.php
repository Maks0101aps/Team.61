<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\ParentModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ParentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $account = Account::first() ?? Account::create(['name' => 'School Account']);
        
        $parentsData = [
            [
                'first_name' => 'Петро',
                'middle_name' => 'Іванович',
                'last_name' => 'Петренко',
                'email' => 'petrenko.p@example.com',
                'phone' => '380501111111',
                'parent_type' => ParentModel::TYPE_FATHER,
                'address' => 'вул. Хрещатик, 1',
                'city' => 'Київ',
                'region' => 'Київська область',
                'country' => 'UA',
                'postal_code' => '01001',
            ],
            [
                'first_name' => 'Олена',
                'middle_name' => 'Василівна',
                'last_name' => 'Петренко',
                'email' => 'petrenko.o@example.com',
                'phone' => '380502222222',
                'parent_type' => ParentModel::TYPE_MOTHER,
                'address' => 'вул. Хрещатик, 1',
                'city' => 'Київ',
                'region' => 'Київська область',
                'country' => 'UA',
                'postal_code' => '01001',
            ],
            [
                'first_name' => 'Андрій',
                'middle_name' => 'Миколайович',
                'last_name' => 'Іваненко',
                'email' => 'ivanenko.a@example.com',
                'phone' => '380503333333',
                'parent_type' => ParentModel::TYPE_FATHER,
                'address' => 'вул. Володимирська, 10',
                'city' => 'Київ',
                'region' => 'Київська область',
                'country' => 'UA',
                'postal_code' => '01001',
            ],
            [
                'first_name' => 'Марія',
                'middle_name' => 'Олександрівна',
                'last_name' => 'Іваненко',
                'email' => 'ivanenko.m@example.com',
                'phone' => '380504444444',
                'parent_type' => ParentModel::TYPE_MOTHER,
                'address' => 'вул. Володимирська, 10',
                'city' => 'Київ',
                'region' => 'Київська область',
                'country' => 'UA',
                'postal_code' => '01001',
            ],
            [
                'first_name' => 'Сергій',
                'middle_name' => 'Андрійович',
                'last_name' => 'Коваленко',
                'email' => 'kovalenko.s@example.com',
                'phone' => '380505555555',
                'parent_type' => ParentModel::TYPE_FATHER,
                'address' => 'вул. Шевченка, 15',
                'city' => 'Київ',
                'region' => 'Київська область',
                'country' => 'UA',
                'postal_code' => '01001',
            ],
            [
                'first_name' => 'Ольга',
                'middle_name' => 'Петрівна',
                'last_name' => 'Коваленко',
                'email' => 'kovalenko.o@example.com',
                'phone' => '380506666666',
                'parent_type' => ParentModel::TYPE_MOTHER,
                'address' => 'вул. Шевченка, 15',
                'city' => 'Київ',
                'region' => 'Київська область',
                'country' => 'UA',
                'postal_code' => '01001',
            ],
            // Новые семьи
            [
                'first_name' => 'Віктор',
                'middle_name' => 'Павлович',
                'last_name' => 'Мельник',
                'email' => 'melnyk.v@example.com',
                'phone' => '380507777777',
                'parent_type' => ParentModel::TYPE_FATHER,
                'address' => 'вул. Франка, 22',
                'city' => 'Київ',
                'region' => 'Київська область',
                'country' => 'UA',
                'postal_code' => '01001',
            ],
            [
                'first_name' => 'Наталія',
                'middle_name' => 'Ігорівна',
                'last_name' => 'Мельник',
                'email' => 'melnyk.n@example.com',
                'phone' => '380508888888',
                'parent_type' => ParentModel::TYPE_MOTHER,
                'address' => 'вул. Франка, 22',
                'city' => 'Київ',
                'region' => 'Київська область',
                'country' => 'UA',
                'postal_code' => '01001',
            ],
            [
                'first_name' => 'Олег',
                'middle_name' => 'Степанович',
                'last_name' => 'Бондаренко',
                'email' => 'bondarenko.o@example.com',
                'phone' => '380509999999',
                'parent_type' => ParentModel::TYPE_FATHER,
                'address' => 'вул. Лесі Українки, 5',
                'city' => 'Київ',
                'region' => 'Київська область',
                'country' => 'UA',
                'postal_code' => '01001',
            ],
            [
                'first_name' => 'Тетяна',
                'middle_name' => 'Михайлівна',
                'last_name' => 'Бондаренко',
                'email' => 'bondarenko.t@example.com',
                'phone' => '380501234567',
                'parent_type' => ParentModel::TYPE_MOTHER,
                'address' => 'вул. Лесі Українки, 5',
                'city' => 'Київ',
                'region' => 'Київська область',
                'country' => 'UA',
                'postal_code' => '01001',
            ],
            [
                'first_name' => 'Михайло',
                'middle_name' => 'Олексійович',
                'last_name' => 'Шевченко',
                'email' => 'shevchenko.m@example.com',
                'phone' => '380507654321',
                'parent_type' => ParentModel::TYPE_FATHER,
                'address' => 'вул. Сагайдачного, 12',
                'city' => 'Київ',
                'region' => 'Київська область',
                'country' => 'UA',
                'postal_code' => '01001',
            ],
            [
                'first_name' => 'Ірина',
                'middle_name' => 'Володимирівна',
                'last_name' => 'Шевченко',
                'email' => 'shevchenko.i@example.com',
                'phone' => '380509876543',
                'parent_type' => ParentModel::TYPE_MOTHER,
                'address' => 'вул. Сагайдачного, 12',
                'city' => 'Київ',
                'region' => 'Київська область',
                'country' => 'UA',
                'postal_code' => '01001',
            ],
            [
                'first_name' => 'Григорій',
                'middle_name' => 'Петрович',
                'last_name' => 'Савченко',
                'email' => 'savchenko.g@example.com',
                'phone' => '380501122334',
                'parent_type' => ParentModel::TYPE_FATHER,
                'address' => 'вул. Богдана Хмельницького, 30',
                'city' => 'Київ',
                'region' => 'Київська область',
                'country' => 'UA',
                'postal_code' => '01001',
            ],
            [
                'first_name' => 'Катерина',
                'middle_name' => 'Іванівна',
                'last_name' => 'Савченко',
                'email' => 'savchenko.k@example.com',
                'phone' => '380505544332',
                'parent_type' => ParentModel::TYPE_MOTHER,
                'address' => 'вул. Богдана Хмельницького, 30',
                'city' => 'Київ',
                'region' => 'Київська область',
                'country' => 'UA',
                'postal_code' => '01001',
            ],
        ];
        
        foreach ($parentsData as $data) {
            ParentModel::create([
                'account_id' => $account->id,
                'first_name' => $data['first_name'],
                'middle_name' => $data['middle_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'parent_type' => $data['parent_type'],
                'phone' => $data['phone'],
                'address' => $data['address'],
                'city' => $data['city'],
                'region' => $data['region'],
                'country' => $data['country'],
                'postal_code' => $data['postal_code'],
            ]);
        }
        
        // Check if test parent already exists
        $existingTestParent = ParentModel::where('email', 'janedoe@example.com')->first();
        
        if (!$existingTestParent) {
            // Create a test parent account
            ParentModel::create([
                'account_id' => $account->id,
                'first_name' => 'Jane',
                'middle_name' => 'Test',
                'last_name' => 'Doe',
                'email' => 'janedoe@example.com',
                'parent_type' => ParentModel::TYPE_MOTHER,
                'phone' => '380501234567',
                'address' => 'Test Address',
                'city' => 'Kyiv',
                'region' => 'Kyiv Region',
                'country' => 'UA',
                'postal_code' => '01001',
            ]);
            
            $this->command->info('Test parent created successfully. Email: janedoe@example.com');
        } else {
            $this->command->info('Test parent already exists. Skipping creation.');
        }
    }
}
