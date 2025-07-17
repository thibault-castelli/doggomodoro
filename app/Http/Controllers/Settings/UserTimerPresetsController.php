<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserTimerPresets;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class UserTimerPresetsController extends Controller
{
    /**
     * Show the user's timer presets page.
     */
    public function edit()
    {
        return Inertia::render('settings/Timer', [
            'timerPresets' => UserTimerPresets::where('user_id', Auth::id())->firstOrFail(),
        ]);
    }

    /**
     * Update the authenticated user's timer presets.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'work_duration' => ['required', 'integer', 'min:1', 'max:60'],
            'break_duration' => ['required', 'integer', 'min:1', 'max:60'],
            'long_break_duration' => ['required', 'integer', 'min:1', 'max:60'],
            'long_break_interval' => ['required', 'integer', 'min:1', 'max:10'],
            'auto_play' => ['boolean'],
            // 'notifications_enabled' => ['boolean'],
        ]);

        $userTimerPresets = UserTimerPresets::where('user_id', Auth::id())->firstOrFail();
        $userTimerPresets->update($validated);

        return back();
    }
}
