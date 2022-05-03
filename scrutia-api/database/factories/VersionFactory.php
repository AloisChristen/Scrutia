<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\Status;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory
 */
class VersionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $project = Project::factory();
        return [
            'number' => $this->faker->numberBetween(2,9),
            'status' => Status::INITIATIVE,
            'description' => $this->faker->text(),
            'project_id' => $project,
            'user_id' => User::factory(),
        ];
    }
}
