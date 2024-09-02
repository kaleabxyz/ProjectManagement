<?php

namespace Database\Factories;

use App\Models\TeamMember;
use App\Models\User;
use App\Models\Board;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeamMemberFactory extends Factory
{
    protected $model = TeamMember::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $user = User::inRandomOrder()->first()->id;
        $board = Board::inRandomOrder()->first()->id;
        return [
            'board' => $board, // Generate a Board and use its ID
            'member' => $user, // Generate a User and use its ID
            'role' => $this->faker->randomElement(['Member', 'Admin', 'Viewer']), // Example roles
        ];
    }
}
