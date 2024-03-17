<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\QuestionGroup>
 */
class QuestionGroupFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->name(40),
            'disqualify_amount' => $this->faker->randomNumber(1, true),
            'answer_time' => $this->faker->randomFloat(2, 5, 30),
            'points' => $this->faker->randomNumber(1, true),
            'is_additional' => $this->faker->boolean(),
        ];
    }
}
