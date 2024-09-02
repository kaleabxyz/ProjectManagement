<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use App\Models\Board;
use App\Models\Update;
use App\Models\TeamMember;
use Illuminate\Database\Eloquent\Factories\Factory;

class UpdateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Update::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        // Fetch a random board
        $board = Board::inRandomOrder()->first();
        $boardId = $board->id;

        // Fetch a random team member associated with the board
        $teamMember = TeamMember::where('board', $boardId)
            ->inRandomOrder()
            ->first();

        // Fetch a random task associated with the board
        $task = Task::where('board_id', $boardId)
            ->inRandomOrder()
            ->first();

        // Fetch all potential parent updates associated with the board
        $potentialParents = Update::where('board_id', $boardId)->get();

        // Determine if the update should have a parent_id (40% chance) and if there are potential parents
        $hasParent = $this->faker->boolean(40) && $potentialParents->isNotEmpty();

        // If the update should have a parent and there are potential parents, pick a random parent update
        $parentUpdate = $hasParent ? $potentialParents->random() : null;

        // If a team member is found, use their member ID; otherwise, use a random user ID
        $userId = $teamMember ? $teamMember->member : User::inRandomOrder()->first()->id;

        // Randomly decide whether to set task_id to null or a task's ID
        $taskId = $this->faker->boolean(60) ? ($task ? $task->id : null) : null;

        // Set reply to true if there is a parent_id
        $isReply = !is_null($parentUpdate);

        return [
            'task_id' => $taskId,
            'user_id' => $userId,
            'content' => $this->faker->paragraph(),
            'parent_id' => null,
            'reply' => false,
            'read' => $this->faker->boolean(),
            'board_id' => $boardId,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
