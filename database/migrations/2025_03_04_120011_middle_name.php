<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Изменяем таблицу contacts
        Schema::table('contacts', function (Blueprint $table) {
            $table->string('middle_name', 25)->default('')->nullable(true);
        });

        // Изменяем таблицу users
        Schema::table('users', function (Blueprint $table) {
            $table->string('middle_name', 25)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Отменяем изменения в таблице users
        Schema::table('users', function (Blueprint $table) {
            $table->string('middle_name', 25)->nullable(false)->change();
        });

        // Отменяем изменения в таблице contacts
        Schema::table('contacts', function (Blueprint $table) {
            $table->string('middle_name', 25)->nullable(false)->default('')->change();
        });
    }
};
