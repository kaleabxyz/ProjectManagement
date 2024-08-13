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

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->geometry('user_name', 'linestring')->index();
            $table->geometry('email', 'linestring')->unique();
            $table->geometry('password', 'linestring');
            $table->geometry('first_name', 'linestring');
            $table->geometry('last_name', 'linestring');
            $table->text('profile_picture_url')->nullable();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
