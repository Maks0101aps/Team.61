<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if account already exists
        $account = Account::first();
        if (!$account) {
            $account = Account::create(['name' => 'School 61']);
        }

        // Check if user already exists
        $existingUser = User::where('email', 'johndoe@example.com')->first();
        
        if (!$existingUser) {
            User::create([
                'account_id' => $account->id,
                'first_name' => 'John',
                'middle_name' => '',
                'last_name' => 'Doe',
                'email' => 'johndoe@example.com',
                'password' => Hash::make('secret'),
                'owner' => true,
                'role' => User::ROLE_TEACHER,
            ]);
            
            $this->command->info('User created successfully. Email: johndoe@example.com, Password: secret');
        } else {
            $this->command->info('User already exists. Skipping creation.');
        }
    }
}
