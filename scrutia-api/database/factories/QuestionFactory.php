<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Version;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory
 */
class QuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->text(50),
            'version_id' => Version::pluck('id')[$this->faker->numberBetween(1, Version::count() - 1)],
            'user_id' => User::pluck('id')[$this->faker->numberBetween(1, User::count() - 1)],
        ];
    }
}
