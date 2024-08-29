<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\Board;
use App\Models\User;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Task::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $boardId = Board::inRandomOrder()->first()->id;
        $userId = User::inRandomOrder()->first()->id;

        $startDate = Carbon::now()->subYear()->startOfYear(); // Start of last year
    $endDate = Carbon::now();

        return [
            'task_name' =>$this->faker->randomElement(['task 1', 'task 2', 'task 3']) ,
            
            'status' => $this->faker->randomElement(['Not Started', 'In Progress', 'Completed']),
            'priority' => $this->faker->randomElement(['Low', 'Medium', 'High']),
            'due_date' => $this->faker->dateTimeBetween('now', '+1 year'),
            'board_id' => $boardId,
            'assigned_to' => $userId, // Placeholder for user ID
            'budget' => $this->faker->numberBetween(100, 10000),
            'notes' => $this->faker->randomElement(['note1','note2']),
            'is_trashed' => $this->faker->boolean(10), // 10% chance of being trashed
            'trashed_at' => $this->faker->boolean(10) ? $this->faker->dateTimeBetween('-1 month', 'now') : null,
            'trashed_by' => null, // Placeholder for user ID
            'column' => $this->faker->randomDigit(),
            'created_at' => $this->faker->dateTimeBetween($startDate, $endDate),
            'updated_at' => now(),
        ];
    }
}
