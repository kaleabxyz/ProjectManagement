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
        Schema::disableForeignKeyConstraints();

        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('task_name')->index();
            $table->longText('description')->nullable();
            $table->string('status');
            $table->string('priority');
            $table->dateTime('due_date');
            $table->bigInteger('board_id');
            $table->foreign('board_id')->references('board_id')->on('boards');
            $table->bigInteger('assigned_to')->nullable();
            $table->foreign('assigned_to')->references('user_id')->on('users');
            $table->bigInteger('budget')->nullable();
            $table->longText('notes')->nullable();
            $table->longText('dependencies')->nullable();
            $table->string('label')->nullable();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
