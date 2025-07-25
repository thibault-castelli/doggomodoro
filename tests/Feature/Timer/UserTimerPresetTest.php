<?php

namespace Tests\Feature\Timer;

use App\Models\User;
use App\Models\UserTimerPreset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTimerPresetTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        // Create a user for authentication
        $this->user = User::factory()->create();
    }

    public function test_guest_cannot_access_presets_routes()
    {
        $this->get('/presets')->assertRedirect('/login');
        $this->get('/presets/create')->assertRedirect('/login');
        $this->post('/presets')->assertRedirect('/login');
    }

    public function test_user_can_view_presets_page()
    {
        $this->actingAs($this->user)
            ->get('/presets')
            ->assertStatus(200)
            ->assertInertia(fn($page) => $page->component('presets/Presets'));
    }

    public function test_user_can_create_a_preset()
    {
        $this->actingAs($this->user);

        $data = [
            'name' => 'Test Preset',
            'work_duration' => 20,
            'break_duration' => 5,
            'long_break_duration' => 15,
            'long_break_interval' => 4,
            'notifications_enabled' => true,
            'auto_play' => false,
        ];

        $response = $this->post('/presets', $data);

        $response->assertRedirect(route('presets'));
        $this->assertDatabaseHas('user_timer_presets', [
            'user_id' => $this->user->id,
            'name' => 'Test Preset',
        ]);
    }

    public function test_user_can_edit_a_preset()
    {
        $preset = UserTimerPreset::factory()->create(['user_id' => $this->user->id]);

        $this->actingAs($this->user)
            ->get("/presets/{$preset->id}")
            ->assertStatus(200)
            ->assertInertia(fn($page) => $page->component('presets/CreateEditPreset'));
    }

    public function test_user_cannot_edit_preset_of_other_user()
    {
        $otherUser = User::factory()->create();
        $preset = UserTimerPreset::factory()->create(['user_id' => $otherUser->id]);

        $this->actingAs($this->user);

        $response = $this->get("/presets/{$preset->id}");

        $response->assertStatus(404);
    }


    public function test_user_can_update_a_preset()
    {
        $preset = UserTimerPreset::factory()->create(['user_id' => $this->user->id]);

        $this->actingAs($this->user);

        $data = [
            'name' => 'Updated Preset',
            'work_duration' => 30,
            'break_duration' => 10,
            'long_break_duration' => 20,
            'long_break_interval' => 5,
            'notifications_enabled' => false,
            'auto_play' => true,
        ];

        $response = $this->put("/presets/{$preset->id}", $data);

        $response->assertRedirect(route('presets'));
        $this->assertDatabaseHas('user_timer_presets', [
            'id' => $preset->id,
            'name' => 'Updated Preset',
        ]);
    }

    public function test_user_cannot_update_preset_form_other_user()
    {
        $otherUser = User::factory()->create();
        $preset = UserTimerPreset::factory()->create(['user_id' => $otherUser->id]);

        $this->actingAs($this->user);

        $data = [
            'name' => 'Malicious Update',
            'work_duration' => 25,
            'break_duration' => 5,
            'long_break_duration' => 15,
            'long_break_interval' => 4,
            'notifications_enabled' => false,
            'auto_play' => false,
        ];

        $response = $this->put("/presets/{$preset->id}", $data);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('user_timer_presets', [
            'id' => $preset->id,
            'name' => 'Malicious Update',
        ]);
    }

    public function test_user_can_delete_a_preset()
    {
        $preset = UserTimerPreset::factory()->create(['user_id' => $this->user->id]);
        // Create a second preset so deletion is allowed
        UserTimerPreset::factory()->create(['user_id' => $this->user->id]);

        $this->actingAs($this->user);

        $response = $this->delete("/presets/{$preset->id}");

        $response->assertRedirect(route('presets'));
        $this->assertDatabaseMissing('user_timer_presets', [
            'id' => $preset->id,
        ]);
    }

    public function test_user_cannot_delete_last_preset()
    {
        $preset = UserTimerPreset::factory()->create(['user_id' => $this->user->id]);

        $this->actingAs($this->user);

        $response = $this->delete("/presets/{$preset->id}");

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('user_timer_presets', [
            'id' => $preset->id,
        ]);
    }

    public function test_user_cannot_delete_preset_of_other_user()
    {
        $otherUser = User::factory()->create();
        $preset = UserTimerPreset::factory()->create(['user_id' => $otherUser->id]);

        $this->actingAs($this->user);

        $response = $this->delete("/presets/{$preset->id}");

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('user_timer_presets', [
            'id' => $preset->id,
        ]);
    }
}