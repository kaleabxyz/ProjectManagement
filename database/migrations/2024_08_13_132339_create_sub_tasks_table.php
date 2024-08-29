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
            $table->boolean('selectStatus')->default(false);
            $table->boolean('selectOwner')->default(false);
            $table->boolean('selectPriority')->default(false);
            $table->boolean('showUpdates')->default(false);

            $table->string('status');
            $table->string('priority');
            $table->dateTime('due_date');
            $table->bigInteger('board_id')->unsigned();
            $table->bigInteger('assigned_to')->nullable()->unsigned();
            $table->foreign('assigned_to')->references('id')->on('users')->onUpdate('cascade');
            $table->bigInteger('budget')->nullable();
            $table->longText('notes')->nullable();
            $table->boolean('is_trashed')->nullable();
            $table->timestamp('trashed_at')->nullable();
            $table->bigInteger('trashed_by')->unsigned();
            $table->foreign('trashed_by')->references('id')->on('users')->onUpdate('cascade');
            $table->integer('column')->nullable();
            $table->bigInteger('task_id')->unsigned();
            $table->foreign('task_id')->references('id')->on('tasks')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
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
