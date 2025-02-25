<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
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
            $answer_input = "<br><input type=\"text\" name=\"answer\" placeholder=\"". $right_answer. "\">";
        } else {
            $answer_input = "<input type=\"radio\" id=\"choice1\" name=\"answer\" value=\"option1\">
            <label for=\"choice1\">" . fake()->text(10) . "</label><br>
            <input type=\"radio\" id=\"choice2\" name=\"answer\" value=\"option1\">
            <label for=\"choice2\">" . fake()->text(10) . "</label><br>
            <input type=\"radio\" id=\"choice3\" name=\"answer\" value=\"" . $right_answer . "\">
            <label for=\"choice3\">" . $right_answer . "</label><br>
            <input type=\"radio\" id=\"choice4\" name=\"answer\" value=\"option1\">
            <label for=\"choice4\">" . fake()->text(10) . "</label><br>";
        }
        return [
            'title' => fake()->text(10),
            'content' => fake()->text() . "<br>" . $answer_input,
            'answer' => $right_answer,
        ];
    }
}
