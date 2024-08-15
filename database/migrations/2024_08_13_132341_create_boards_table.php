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
            $table->bigInteger('project_id')->unsigned();  // Foreign key for projects
            $table->foreign('project_id')->references('id')->on('projects')->onUpdate('cascade'); 
            $table->string('board_name')->index();
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
