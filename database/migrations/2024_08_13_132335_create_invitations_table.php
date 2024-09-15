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

        Schema::create('invitations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('invtier')->index()->unsigned();
            $table->foreign('inviter')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('invited')->index()->unsigned();
            $table->foreign('invited')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('board')->index()->unsigned();
            $table->bigInteger('board')->index()->unsigned();
            $table->foreign('team')->references('id')->on('boards')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('team')->references('id')->on('teams')->onDelete('cascade')->onUpdate('cascade');
            $table->string('status');
            $table->string('role');

            $table->string('email');
            $table->string('token')->unique();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invitations');
    }
};
