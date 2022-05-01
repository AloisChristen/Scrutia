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
        $version_id = null;
        $user_id = null;

        if (User::count() != 0)
            $user_id = User::pluck('id')[$this->faker->numberBetween(1, User::count() - 1)];
        if(Version::count() != 0)
            $version_id = Version::pluck('id')[$this->faker->numberBetween(1, Version::count() - 1)];
        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->text(50),
            'version_id' => $version_id,
            'user_id' => $user_id,
        ];
    }
}
