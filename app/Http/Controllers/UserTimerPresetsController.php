<?php

namespace App\Http\Controllers;

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
        return Inertia::render('Presets', [
            'timerPresets' => UserTimerPresets::where('user_id', Auth::id())->get(),
        ]);
    }

    /**
     * Update the authenticated user's timer presets.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'work_duration' => ['required', 'integer', 'min:1', 'max:60'],
                'break_duration' => ['required', 'integer', 'min:1', 'max:60'],
                'long_break_duration' => ['required', 'integer', 'min:1', 'max:60'],
                'long_break_interval' => ['required', 'integer', 'min:1', 'max:10'],
                'auto_play' => ['boolean'],
                // 'notifications_enabled' => ['boolean'],
            ]);

            $userTimerPresets = UserTimerPresets::where(['user_id' => Auth::id(), 'id' => $id])->firstOrFail();
            $userTimerPresets->update($validated);

            return back()->with('success', "Timer preset '{$userTimerPresets->name}' updated successfully.");
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update timer preset: ' . $e->getMessage());
        }
    }
}
