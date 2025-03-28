<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // По умолчанию делаем всех существующих пользователей учителями
        DB::table('users')
            ->whereNull('role')
            ->update(['role' => User::ROLE_TEACHER]);
    }

    /**
     * Reverse the migrations.
     * 
     * В случае отката, мы не можем знать, у кого какая роль была,
     * поэтому откатывать не будем.
     */
    public function down(): void
    {
        // В данном случае откат не требуется
    }
};
