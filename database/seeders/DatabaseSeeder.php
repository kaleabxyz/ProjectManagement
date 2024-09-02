<?php

namespace Database\Seeders;
use App\Models\Task;
use App\Models\User;

use App\Models\Board;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\TeamMember;
use App\Models\Update;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        User::factory()->count(18)->create();
        Board::factory()->count(5)->create();
        TeamMember::factory()->count(20)->create();
        Task::factory()->count(20)->create();
        Update::factory()->count(50)->create();

    }
}
