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

        Schema::create('boards', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('folder_id')->unsigned()->nullable();  
            $table->foreign('folder_id')->references('id')->on('folders')->onUpdate('cascade'); 
            $table->bigInteger('team')->unsigned()->nullable();  // Foreign key for projects
            $table->foreign('team')->references('id')->on('teams')->onUpdate('cascade'); 
            $table->boolean('is_favorite')->default(false);
            $table->boolean('is_archived')->default(false);
            $table->string('board_name')->index();
            $table->longText('discription')->nullable();
            $table->bigInteger('owner')->unsigned()->nullable();
            $table->foreign('owner')->references('id')->on('users')->onUpdate('cascade');
            $table->boolean('is_trashed')->default(false);
            $table->timestamp('trashed_at')->nullable();
            $table->bigInteger('created_by')->unsigned()->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onUpdate('cascade');
            $table->bigInteger('trashed_by')->unsigned()->nullable();
            $table->foreign('trashed_by')->references('id')->on('users')->onUpdate('cascade');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boards');
    }
};
