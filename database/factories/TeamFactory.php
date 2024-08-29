<?php

namespace Database\Factories;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeamFactory extends Factory
{
    protected $model = Team::class;

    public function definition()
    {
        return [
            'team_name' => $this->faker->word(),
            'owner_id' => User::factory(), // Assigns a user as the owner
        ];
    }
}
