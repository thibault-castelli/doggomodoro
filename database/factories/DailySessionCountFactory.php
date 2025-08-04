<?php

namespace Database\Factories;

use App\Models\DailySessionCount;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DailySessionCount>
 */
class DailySessionCountFactory extends Factory
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
            'sessions_completed' => $this->faker->numberBetween(0, 10),
            'created_at' => $this->faker->dateTimeBetween('-3 month', 'now'),
        ];
    }
}
