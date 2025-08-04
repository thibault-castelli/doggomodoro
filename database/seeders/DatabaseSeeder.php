<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\UserTimerPreset;
use App\Models\UserTimerStats;
use App\Models\DailySessionCount;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $testUser = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        UserTimerPreset::factory(2)->create([
            'user_id' => $testUser->id,
        ]);

        UserTimerStats::factory(1)->create([
            'user_id' => $testUser->id,
        ]);

        DailySessionCount::factory(30)->create([
            'user_id' => $testUser->id,
        ]);
    }
}
