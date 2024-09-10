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

        Schema::create('updates', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('task_id')->index()->unsigned()->nullable();
            $table->foreign('task_id')->references('id')->on('tasks')->onUpdate('cascade')->onDelete('cascade');
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->longText('content');
            $table->boolean('has_reply')->default(false);
            $table->boolean('bookmark')->default(false);

            $table->boolean('reply')->nullable();
            $table->bigInteger('parent_id')->unsigned()->nullable(); // New column
            $table->foreign('parent_id')->references('id')->on('updates')->onUpdate('cascade')->onDelete('cascade'); // Foreign key for replies
            $table->boolean('read')->default(false);
            $table->bigInteger('board_id')->unsigned();
            $table->foreign('board_id')->references('id')->on('boards')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('updates');
    }
};

