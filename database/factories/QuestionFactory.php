<?php

namespace Database\Factories;

use App\Models\Quiz;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // 'question_type_id' => $this->faker->numberBetween(1, 2),
            'question' => $this->faker->sentence,
            'answer' => $this->faker->numberBetween(1, 3),
        ];
    }
}
