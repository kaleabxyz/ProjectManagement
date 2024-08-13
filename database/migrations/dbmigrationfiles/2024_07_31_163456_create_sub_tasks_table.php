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

        Schema::create('sub_tasks', function (Blueprint $table) {
            $table->id();
            $table->geometry('task_name', 'linestring')->index();
            $table->geometry('description', 'multilinestring')->nullable();
            $table->geometry('status', 'linestring');
            $table->geometry('priority', 'linestring');
            $table->dateTime('due_date');
            $table->bigInteger('board_id');
            $table->bigInteger('assigned_to')->nullable();
            $table->bigInteger('budget')->nullable();
            $table->geometry('notes', 'multilinestring')->nullable();
            $table->geometry('dependencies', 'multilinestring')->nullable();
            $table->geometry('label', 'linestring')->nullable();
            $table->bigInteger('task_id');
            $table->foreign('task_id')->references('task_id')->on('tasks');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_tasks');
    }
};
