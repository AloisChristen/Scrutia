<?php

namespace Database\Factories;

use App\Models\Answer;
use App\Models\Question;
use App\Models\User;
use App\Models\Version;
use App\Models\Vote;
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
            Answer::factory()->create(),
            Question::factory()->create(),
            Version::factory()->create(),
        ];

        $likeable = Arr::random( $likeables )::inRandomOrder()->first();
        return [
            'value' => $this->faker->randomElement([Vote::UPVOTE, Vote::DOWNVOTE]),
            'user_id' => User::factory(),
            'likeable_id' => $likeable->id,
            'likeable_type' => $likeable->getMorphClass(),
        ];
    }
}
