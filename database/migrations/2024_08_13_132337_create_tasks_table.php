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
            $table->string('status')->default("Not Started");
            $table->boolean('selectStatus')->default(false);
            $table->boolean('selectOwner')->default(false);
            $table->boolean('selectPriority')->default(false);
            $table->boolean('showUpdates')->default(false);
            
            $table->string('priority')->default("Medium");
            $table->dateTime('due_date')->nullable();
            $table->bigInteger('board_id')->unsigned();
            $table->foreign('board_id')->references('id')->on('boards')->onUpdate('cascade');
            $table->bigInteger('assigned_to')->nullable()->unsigned();
            $table->foreign('assigned_to')->references('id')->on('users')->onUpdate('cascade');
            $table->bigInteger('budget')->default(0);
            $table->longText('notes')->nullable();
            $table->boolean('is_trashed')->nullable();
            $table->timestamp('trashed_at')->nullable();
            $table->bigInteger('trashed_by')->unsigned()->nullable();
            $table->foreign('trashed_by')->references('id')->on('users')->onUpdate('cascade');
            $table->integer('column')->nullable();
            $table->boolean('subItemVisible')->default(false);
            $table->timestamps();
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
