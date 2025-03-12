<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Teacher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $account = Account::first();

        if ($account) {
            Teacher::factory()
                ->count(20)
                ->create([
                    'account_id' => $account->id,
                ]);
        }
    }
}
