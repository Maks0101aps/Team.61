<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create account first if it doesn't exist
        $account = Account::first();
        if (!$account) {
            $account = Account::create([
                'name' => 'Test Account',
            ]);
        }
        
        // Create a test user
        User::create([
            'account_id' => $account->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'johndoe@example.com',
            'password' => Hash::make('secret'), // Plain text password is 'secret'
            'owner' => true,
        ]);
        
        $this->command->info('Test user created successfully. Email: johndoe@example.com, Password: secret');
    }
} 