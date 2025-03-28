<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $account = Account::create(['name' => 'School 61']);

        User::create([
            'account_id' => $account->id,
            'first_name' => 'John',
            'middle_name' => '',
            'last_name' => 'Doe',
            'email' => 'johndoe@example.com',
            'password' => 'secret',
            'owner' => true,
            'role' => User::ROLE_TEACHER,
        ]);
    }
}
