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
        
        // Test teacher account
        $existingTeacher = User::where('email', 'johndoe@example.com')->first();
        
        if (!$existingTeacher) {
            // Create a test teacher only if it doesn't exist
            User::create([
                'account_id' => $account->id,
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'johndoe@example.com',
                'password' => Hash::make('secret'), // Plain text password is 'secret'
                'owner' => true,
                'role' => User::ROLE_TEACHER,
            ]);
            
            $this->command->info('Test teacher created successfully. Email: johndoe@example.com, Password: secret');
        } else {
            $this->command->info('Test teacher already exists. Skipping creation.');
        }
        
        // Test parent account
        $existingParent = User::where('email', 'janedoe@example.com')->first();
        
        if (!$existingParent) {
            // Create a test parent only if it doesn't exist
            User::create([
                'account_id' => $account->id,
                'first_name' => 'Jane',
                'last_name' => 'Doe',
                'email' => 'janedoe@example.com',
                'password' => Hash::make('secret'), // Plain text password is 'secret'
                'owner' => false,
                'role' => User::ROLE_PARENT,
            ]);
            
            $this->command->info('Test parent created successfully. Email: janedoe@example.com, Password: secret');
        } else {
            $this->command->info('Test parent already exists. Skipping creation.');
        }
        
        // Test student account
        $existingStudent = User::where('email', 'jimmydoe@example.com')->first();
        
        if (!$existingStudent) {
            // Create a test student only if it doesn't exist
            User::create([
                'account_id' => $account->id,
                'first_name' => 'Jimmy',
                'last_name' => 'Doe',
                'email' => 'jimmydoe@example.com',
                'password' => Hash::make('secret'), // Plain text password is 'secret'
                'owner' => false,
                'role' => User::ROLE_STUDENT,
            ]);
            
            $this->command->info('Test student created successfully. Email: jimmydoe@example.com, Password: secret');
        } else {
            $this->command->info('Test student already exists. Skipping creation.');
        }
    }
} 