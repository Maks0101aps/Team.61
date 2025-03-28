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
        Schema::table('parent_models', function (Blueprint $table) {
            // Добавляем поля для улицы и номера дома
            $table->string('street', 150)->nullable()->after('address');
            $table->string('house_number', 50)->nullable()->after('street');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parent_models', function (Blueprint $table) {
            // Удаляем поля
            $table->dropColumn(['street', 'house_number']);
        });
    }
};
