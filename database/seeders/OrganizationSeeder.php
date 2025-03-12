<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Organization;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $account = Account::first();

        if ($account) {
            // Create main school organization
            Organization::create([
                'name' => 'Школа №61',
                'email' => 'school61@example.com',
                'phone' => '+380441234567',
                'address' => 'вул. Шкільна, 1',
                'city' => 'Київ',
                'region' => 'Київська область',
                'country' => 'UA',
                'postal_code' => '01001',
                'account_id' => $account->id,
            ]);

            // Create additional departments
            $departments = [
                ['name' => 'Початкова школа', 'email' => 'elementary@school61.example.com'],
                ['name' => 'Середня школа', 'email' => 'middle@school61.example.com'],
                ['name' => 'Старша школа', 'email' => 'high@school61.example.com'],
                ['name' => 'Спортивний відділ', 'email' => 'sports@school61.example.com'],
                ['name' => 'Мистецький відділ', 'email' => 'arts@school61.example.com']
            ];

            foreach ($departments as $department) {
                Organization::create([
                    'name' => $department['name'] . ' - Школа №61',
                    'email' => $department['email'],
                    'phone' => '+38044' . rand(1000000, 9999999),
                    'address' => 'вул. Шкільна, 1',
                    'city' => 'Київ',
                    'region' => 'Київська область',
                    'country' => 'UA',
                    'postal_code' => '01001',
                    'account_id' => $account->id,
                ]);
            }
        }
    }
}
