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

        Schema::create('team_members', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('member')->unsigned();
            $table->foreign('member')->references('id')->on('users')->onUpdate('cascade');
            $table->bigInteger('board')->unsigned();
            $table->foreign('board')->references('id')->on('boards')->onUpdate('cascade')->onDelete('cascade');
            $table->string('role')->default('Member');
            $table->index(['board', 'member']);
            $table->timestamps();

        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_members');
    }
};
