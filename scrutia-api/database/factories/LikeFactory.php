<?php

namespace Database\Factories;

use App\Models\Answer;
use App\Models\Question;
use App\Models\User;
use App\Models\Version;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Arr;

/**
 * @extends Factory
 */
class LikeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $likeables = [
            Answer::class,
            Question::class,
            Version::class,
        ];

        $likeable = Arr::random( $likeables )::inRandomOrder()->first();
        $user_id = null;
        if(User::count() != 0)
            $user_id = User::pluck('id')[$this->faker->numberBetween(1, User::count() - 1)];

        return [
            'value' => $this->faker->randomElement([-1, 1]),
            'user_id' => $user_id,
            'likeable_id' => $likeable->id,
            'likeable_type' => $likeable->getMorphClass(),
        ];
    }
}
