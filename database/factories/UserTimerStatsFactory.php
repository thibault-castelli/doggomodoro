<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserTimerStats;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UserTimerStats>
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
            'user_id' => User::factory(),
            'total_work_time' => $this->faker->numberBetween(0, 9999),
            'total_break_time' => $this->faker->numberBetween(0, 9999),
            'total_rounds_completed' => $this->faker->numberBetween(0, 9999),
            'total_sessions_completed' => $this->faker->numberBetween(0, 9999),
        ];
    }
}
