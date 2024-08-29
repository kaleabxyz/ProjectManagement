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

        Schema::create('folders', function (Blueprint $table) {
            $table->id();
            $table->string('folder_name')->index();
            $table->bigInteger('team_id')->unsigned();
            $table->bigInteger('workspace_id')->unsigned();
            $table->foreign('workspace_id')->references('id')->on('workspaces')->onUpdate('cascade');
            $table->boolean('is_favorite')->nullable();
            $table->boolean('is_archived')->nullable();
            $table->foreign('team_id')->references('id')->on('teams')->onUpdate('cascade');
            $table->boolean('is_trashed')->nullable();
            $table->timestamp('trashed_at')->nullable();
            $table->bigInteger('trashed_by')->unsigned();
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
        Schema::dropIfExists('folders');
    }
};
