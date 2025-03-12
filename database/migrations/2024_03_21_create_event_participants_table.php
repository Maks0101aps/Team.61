<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('event_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->morphs('participant');
            $table->timestamps();
            
            $table->unique(['event_id', 'participant_id', 'participant_type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('event_participants');
    }
}; 