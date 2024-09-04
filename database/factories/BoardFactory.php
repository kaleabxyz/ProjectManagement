<?php

namespace Database\Factories;

use App\Models\Board;
use App\Models\Workspace;
use App\Models\Folder;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BoardFactory extends Factory
{
    protected $model = Board::class;


    public function definition()
    {
        $userId = User::inRandomOrder()->first()->id;
        return [
            'workspace_id' => Workspace::factory(), // Creates a new workspace
            'folder_id' => null, // Creates a new folder, or set it to null if no folder is needed
            'team' => Team::factory(), // Creates a new team
            'is_favorite' => $this->faker->boolean(),
            'discription' => $this->faker->paragraphs(5, true),
            
            'is_archived' => $this->faker->boolean(),
            'board_name' => $this->faker->word(),
            'is_trashed' => $this->faker->boolean(),
            'trashed_at' => $this->faker->boolean() ? $this->faker->dateTimeThisYear() : null,
            'trashed_by' => $this->faker->boolean() ? $userId : null, // User who trashed the board
            'owner' => $userId,
            'created_by' => $userId,

        ];
    }
}
