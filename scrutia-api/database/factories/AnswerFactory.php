<?php

namespace Database\Factories;

use App\Models\Question;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory
 */
class AnswerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $question_id = null;
        $user_id = null;
        if(Question::count() != 0)
            $question_id = Question::pluck('id')[$this->faker->numberBetween(1, Question::count() - 1)];
        if(User::count() != 0)
            $user_id = User::pluck('id')[$this->faker->numberBetween(1, User::count() - 1)];

        return [
            'title' => $this->faker->sentence(),
            'content' => $this->faker->text(),
            'question_id' =>  $question_id,
            'user_id' => $user_id,
        ];
    }
}
