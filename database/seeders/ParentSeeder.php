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
        
        ParentModel::create([
            'account_id' => $account->id,
            'first_name' => 'Test',
            'middle_name' => 'Parent',
            'last_name' => 'User',
            'email' => 'testparent@example.com',
            'phone' => '555-123-4567',
            'address' => '123 Test St',
            'city' => 'Test City',
            'region' => 'Test Region',
            'country' => 'UA',
            'postal_code' => '12345',
        ]);
    }
}
