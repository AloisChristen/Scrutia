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
        $project_id = Project::pluck('id')[$this->faker->numberBetween(1, Project::count() - 1)];
        $project = Project::find($project_id);
        return [
            'number' => $this->faker->randomDigitNot(1),
            'status' => Status::INITIATIVE,
            'description' => $this->faker->text(),
            'project_id' => $project_id,
            'author' => $project->user->id,
        ];
    }
}
