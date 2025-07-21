<?php

namespace Database\Factories;

use App\Models\UserTimerPreset;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UserTimerPreset>
 */
class UserTimerPresetFactory extends Factory
{
    protected $model = UserTimerPreset::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'name' => $this->faker->words(2, true) . ' Preset',
            'work_duration' => $this->faker->numberBetween(15, 60),
            'break_duration' => $this->faker->numberBetween(3, 15),
            'long_break_duration' => $this->faker->numberBetween(10, 30),
            'long_break_interval' => $this->faker->numberBetween(2, 8),
            'notifications_enabled' => $this->faker->boolean(),
            'auto_play' => $this->faker->boolean(),
        ];
    }
}
