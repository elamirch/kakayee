<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'phone_number' => '09'.fake()->unique()->numerify('#########'),
            'email' => fake()->unique()->safeEmail(),
            'name' => fake()->name(),
            'role' => 'user',
            'xp' => fake()->numberBetween(0, 2000),
            'heart' => 5,
            'gems' => 100,
            'daily_goal' => 20,
            'current_streak' => 0,
            'longest_streak' => 0,
            'streak_freezes_available' => 0,
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }
}
