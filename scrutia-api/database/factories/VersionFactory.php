<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\Status;
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
        $project_id = null;
        if(Project::count() != 0)
            $project_id = Project::pluck('id')[$this->faker->numberBetween(1, Project::count() - 1)];

        $project = Project::with('versions')->find($project_id);
        return [
            'number' => $this->faker->numberBetween(2,9),
            'status' => Status::INITIATIVE,
            'description' => $this->faker->text(),
            'project_id' => $project_id,
            'user_id' => $project->user->id,
        ];
    }
}
