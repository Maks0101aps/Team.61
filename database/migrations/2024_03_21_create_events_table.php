<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('type', ['exam', 'test', 'school_event', 'parent_meeting', 'personal']);
            $table->dateTime('start_date');
            $table->integer('duration')->comment('Duration in minutes');
            $table->text('content')->nullable();
            $table->boolean('is_content_hidden')->default(false);
            $table->string('location')->nullable();
            $table->string('online_link')->nullable();
            $table->text('tasks')->nullable();
            $table->foreignId('account_id')->constrained()->cascadeOnDelete();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('events');
    }
}; 