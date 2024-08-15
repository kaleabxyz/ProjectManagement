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
            $table->string('task_name')->index();
            $table->longText('description')->nullable();
            $table->string('status');
            $table->string('priority');
            $table->dateTime('due_date');
            $table->bigInteger('board_id')->unsigned();
            $table->bigInteger('assigned_to')->nullable()->unsigned();
            $table->bigInteger('budget')->nullable();
            $table->longText('notes')->nullable();
            $table->longText('dependencies')->nullable();
            $table->text('label')->nullable();
            $table->bigInteger('task_id')->unsigned();
            $table->foreign('task_id')->references('id')->on('tasks')->onUpdate('cascade')->onDelete('cascade');
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
