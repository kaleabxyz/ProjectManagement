<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserUpdateReadStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_update_read_status', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Reference to the user
            $table->foreignId('update_id')->constrained('updates')->onDelete('cascade'); // Reference to the update
            $table->boolean('is_read')->default(false); // Read status for the update
            $table->timestamps();

            // Unique constraint to prevent duplicate entries for the same user-update pair
            $table->unique(['user_id', 'update_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_update_read_status');
    }
}

