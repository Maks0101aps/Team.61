<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParentModelsTable extends Migration
{
    public function up()
    {
        Schema::create('parent_models', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('phone')->nullable();
            $table->string('city')->nullable();
            $table->foreignId('account_id')->constrained()->onDelete('cascade'); // Связь с таблицей accounts
            $table->timestamps();
            $table->softDeletes(); // Если вы хотите использовать мягкое удаление
        });
    }

    public function down()
    {
        Schema::dropIfExists('parent_models');
    }
}
