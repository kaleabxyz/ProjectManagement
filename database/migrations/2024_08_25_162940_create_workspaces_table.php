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
        Schema::create('workspaces', function (Blueprint $table) {
            $table->id();
            $table->string('workspace_name');
            $table->longText('description')->nullable();
            $table->boolean('is_favorite')->default(false);
            $table->boolean('is_archived')->default(false);
            $table->bigInteger('created_by')->unsigned(); // Add this line
            $table->foreign('created_by')->references('id')->on('users')->onUpdate('cascade'); // Add this line      
            $table->boolean('is_trashed')->default(false);
            $table->timestamp('trashed_at')->nullable();
            $table->bigInteger('trashed_by')->unsigned()->nullable();
            $table->foreign('trashed_by')->references('id')->on('users')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workspaces');
    }
};
