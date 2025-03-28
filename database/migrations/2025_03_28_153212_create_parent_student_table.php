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
        Schema::create('parent_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_model_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained('contacts')->onDelete('cascade');
            $table->timestamps();
            
            // Ensure a student can only be linked to the same parent once
            $table->unique(['parent_model_id', 'student_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parent_student');
    }
};
