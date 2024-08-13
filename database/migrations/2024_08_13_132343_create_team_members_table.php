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
            $table->foreign('team_id')->references('team_id')->on('teams');
            $table->bigInteger('user_id')->primary();
            $table->foreign('user_id')->references('user_id')->on('users');
            $table->string('role')->nullable();
            $table->index(['team_id', 'user_id']);
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
