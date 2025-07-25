<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserTimerStats>
 */
class UserTimerStatsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'total_work_time' => $this->faker->numberBetween(0, 9999),
            'total_break_time' => $this->faker->numberBetween(0, 9999),
            'total_rounds_completed' => $this->faker->numberBetween(0, 9999),
            'total_sessions_completed' => $this->faker->numberBetween(0, 9999)
        ];
    }
}
