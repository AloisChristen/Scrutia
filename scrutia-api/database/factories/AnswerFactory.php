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
        return [
            'question_id' =>  Question::pluck('id')[$this->faker->numberBetween(1, Question::count() - 1)],
            'author' => User::pluck('id')[$this->faker->numberBetween(1, User::count() - 1)],
            'title' => $this->faker->sentence(),
            'content' => $this->faker->sentences(3, true),
            'description' => $this->faker->text(),
            'question_id' =>  Question::factory(),
            'user_id' => User::factory(),

        ];
    }
}
