<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UserTimerSettings;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class UserTimerSettingsController extends Controller
{
    /**
     * Show the user's timer settings page.
     */
    public function edit()
    {
        return Inertia::render('settings/Timer', [
            'timerSettings' => UserTimerSettings::where('user_id', Auth::id())->firstOrFail(),
        ]);
    }

    /**
     * Update the authenticated user's timer settings.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'work_duration' => ['required', 'integer', 'min:1', 'max:60'],
            'break_duration' => ['required', 'integer', 'min:1', 'max:60'],
            'long_break_duration' => ['required', 'integer', 'min:1', 'max:60'],
            'long_break_interval' => ['required', 'integer', 'min:1', 'max:10'],
            // 'notifications_enabled' => ['boolean'],
            'auto_play' => ['boolean'],
        ]);

        $userTimerSettings = UserTimerSettings::where('user_id', Auth::id())->firstOrFail();
        $userTimerSettings->update($validated);

        return back();
    }
}
