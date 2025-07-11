<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
class CardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $right_answer = fake()->text(10);
        if (mt_rand(0, 1) == 1) {
            ob_start();
            include('component/text-answer-input.php');
            $answer_input = ob_get_clean();
        } else {
            ob_start();
            include('component/4-choice-answer-input.php');
            $answer_input = ob_get_clean();
        }
        return [
            'title' => fake()->text(10),
            'content' => fake()->text() . "<br>" . $answer_input,
            'answer' => $right_answer,
            'date' => fake()->text(10),
            'lesson_id' => rand(1,10),
            'source_id' => 1,
            'explanation' => fake()->text()
        ];
    }
}
