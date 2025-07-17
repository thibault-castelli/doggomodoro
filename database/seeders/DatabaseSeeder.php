<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\UserTimerPresets;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $testUser = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        UserTimerPresets::create([
            'user_id' => $testUser->id,
        ]);
        UserTimerPresets::create([
            'user_id' => $testUser->id,
            'name' => 'Other Preset',
            'work_duration' => 30,
            'break_duration' => 10,
            'long_break_duration' => 20,
            'long_break_interval' => 2,
            'auto_play' => false,
        ]);
    }
}
