<?php

namespace Tests\Feature\Timer;

use App\Models\User;
use App\Models\UserTimerStats;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTimerStatsControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        UserTimerStats::create([
            'user_id' => $this->user->id,
            'total_work_time' => 0,
            'total_break_time' => 0,
            'total_rounds_completed' => 0,
            'total_sessions_completed' => 0,
        ]);
    }

    public function test_show_returns_timer_stats_for_authenticated_user()
    {
        // Arrange
        $this->actingAs($this->user);

        // Act
        $response = $this->get(route('dashboard'));

        // Assert
        $response->assertStatus(200);

        $response->assertInertia(function ($page) {
            $page->component('Dashboard');
        });
    }

    public function test_update_updates_timer_stats_for_authenticated_user()
    {
        // Arrange
        $this->actingAs($this->user);

        $data = [
            'total_work_time' => 100,
            'total_break_time' => 50,
            'total_rounds_completed' => 5,
        ];

        // Act
        $response = $this->put(route('timerStats.update'), $data);

        // Assert
        $response->assertStatus(200);

        $updatedStats = UserTimerStats::forUser($this->user->id);
        $this->assertEquals(100, $updatedStats->total_work_time);
        $this->assertEquals(50, $updatedStats->total_break_time);
        $this->assertEquals(5, $updatedStats->total_rounds_completed);
        $this->assertEquals(1, $updatedStats->total_sessions_completed);
    }

    public function test_update_returns_error_for_unauthenticated_user()
    {
        // Arrange
        $data = [
            'total_work_time' => 100,
            'total_break_time' => 50,
            'total_rounds_completed' => 5,
        ];

        // Act
        $response = $this->put(route('timerStats.update'), $data);

        // Assert
        $response->assertStatus(302);
    }
}
