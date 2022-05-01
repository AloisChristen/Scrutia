<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $user_id = null;
        if (User::count() != 0)
            $user_id = User::pluck('id')[$this->faker->numberBetween(1, User::count() - 1)];

        return [
            'title' => $this->faker->sentence(),
            'user_id' => $user_id,
        ];
    }
}
