<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CardFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => ['en' => fake()->text(10), 'fa' => fake()->text(10)],
            'content' => ['en' => fake()->text(), 'fa' => fake()->text()],
            'answer' => ['en' => fake()->word(), 'fa' => fake()->word()],
            'explanation' => ['en' => fake()->text(), 'fa' => fake()->text()],
            'date' => fake()->date(),
            'order' => 0,
            'lesson_id' => null,
            'source_id' => null,
        ];
    }
}
