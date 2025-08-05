<?php

namespace Tests\Feature\Timer;

use App\Models\DailySessionCount;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DailySessionCountControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_guest_cannot_access_daily_session_count_routes()
    {
        $this->get(route('dailySessionCount', ['from' => '2023-01-01']))
            ->assertStatus(302);

        $this->post(route('dailySessionCount.createOrUpdate'))
            ->assertStatus(302);
    }

    public function test_user_can_get_daily_session_count_with_valid_dates()
    {
        // Arrange
        $from = '2023-01-01';
        $to = '2023-01-31';

        // Create some test data
        DailySessionCount::create([
            'user_id' => $this->user->id,
            'sessions_completed' => 1,
            'created_at' => now()->setDate(2023, 1, 1),
        ]);

        // Act
        $response = $this->actingAs($this->user)
            ->get(route('dailySessionCount', ['from' => $from, 'to' => $to]));

        // Assert
        $response->assertStatus(200)
            ->assertJsonStructure(['dailySessionsCount']);
    }

    public function test_user_gets_error_with_invalid_start_date_format()
    {
        // Arrange
        $from = 'invalid-date';

        // Act
        $response = $this->actingAs($this->user)
            ->get(route('dailySessionCount', ['from' => $from]));

        // Assert
        $response->assertStatus(400)
            ->assertJson(['error' => 'Invalid start date format']);
    }

    public function test_user_gets_error_with_invalid_end_date_format()
    {
        // Arrange
        $from = '2023-01-01';
        $to = 'invalid-date';

        // Act
        $response = $this->actingAs($this->user)
            ->get(route('dailySessionCount', ['from' => $from, 'to' => $to]));

        // Assert
        $response->assertStatus(400)
            ->assertJson(['error' => 'Invalid end date format']);
    }

    public function test_user_gets_error_with_end_date_before_start_date()
    {
        // Arrange
        $from = '2023-01-01';
        $to = '2022-01-01';

        // Act
        $response = $this->actingAs($this->user)
            ->get(route('dailySessionCount', ['from' => $from, 'to' => $to]));

        // Assert
        $response->assertStatus(400);
    }

    public function test_user_can_create_new_daily_session_count_record()
    {
        // Arrange
        $this->actingAs($this->user);

        // Act
        $response = $this->post(route('dailySessionCount.createOrUpdate'));

        // Assert
        $response->assertStatus(200);

        $this->assertDatabaseHas('daily_session_counts', [
            'user_id' => $this->user->id,
            'sessions_completed' => 1,
        ]);
    }

    public function test_user_can_update_existing_daily_session_count_record()
    {
        // Arrange
        $existingRecord = DailySessionCount::create([
            'user_id' => $this->user->id,
            'sessions_completed' => 1,
            'created_at' => now()->setTime(0, 0, 0),
            'updated_at' => now(),
        ]);

        $this->actingAs($this->user);

        // Act
        $response = $this->post(route('dailySessionCount.createOrUpdate'));

        // Assert
        $response->assertStatus(200);

        $this->assertDatabaseHas('daily_session_counts', [
            'id' => $existingRecord->id,
            'sessions_completed' => 2,
        ]);
    }

    public function test_user_cannot_create_record_for_other_user()
    {
        // Arrange
        $otherUser = User::factory()->create();

        // Create a record for another user
        DailySessionCount::create([
            'user_id' => $otherUser->id,
            'sessions_completed' => 1,
            'created_at' => now()->setTime(0, 0, 0),
            'updated_at' => now(),
        ]);

        $this->actingAs($this->user);

        // Act
        $response = $this->post(route('dailySessionCount.createOrUpdate'));

        // Assert
        $response->assertStatus(200);

        // Verify that only the authenticated user's record was created/updated
        $this->assertDatabaseHas('daily_session_counts', [
            'user_id' => $this->user->id,
            'sessions_completed' => 1,
        ]);

        $this->assertDatabaseHas('daily_session_counts', [
            'user_id' => $otherUser->id,
            'sessions_completed' => 1,
        ]);
    }
}
