<?php

namespace Database\Factories;

use App\Models\Workspace;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkspaceFactory extends Factory
{
    protected $model = Workspace::class;

    public function definition()
    {
        return [
            'workspace_name' => $this->faker->word(),
            'is_favorite' => $this->faker->boolean(),
            'is_archived' => $this->faker->boolean(),
            'is_trashed' => $this->faker->boolean(),
            'trashed_at' => $this->faker->boolean() ? $this->faker->dateTimeThisYear() : null,
            'trashed_by' => $this->faker->boolean() ? User::inRandomOrder()->first()->id : null, // Pick a random user for trashed_by
            'created_by' => User::inRandomOrder()->first()->id,
        ];
    }
}
