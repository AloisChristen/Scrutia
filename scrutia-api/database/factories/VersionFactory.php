<?php

namespace Database\Factories;

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
        $number = $this->faker->randomDigitNotNull();
        if($number > 1) {
            $status = Status::INITIATIVE;
        }
        else {
            $status = Status::IDEE;

        }

        return [
            'number' => $number,
            'status' => $status,
            'description' => $this->faker->text(),
        ];
    }
}
