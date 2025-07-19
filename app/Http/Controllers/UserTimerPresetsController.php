<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserTimerPresetRequest;
use App\Http\Controllers\Controller;
use App\Models\UserTimerPresets;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class UserTimerPresetsController extends Controller
{
    /**
     * Show user's timer presets page.
     */
    public function show()
    {
        return Inertia::render('presets/Presets', [
            'timerPresets' => UserTimerPresets::forUser(Auth::id())
        ]);
    }

    /**
     * Show create timer preset page.
     */
    public function create()
    {
        return Inertia::render('presets/CreateEditPreset', [
            'isCreateMode' => true,
            'timerPreset' => new UserTimerPresets(UserTimerPresets::defaultPreset()),
        ]);
    }

    /**
     * Store the user's timer preset
     */
    public function store(UserTimerPresetRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = Auth::id();

        try {
            $userTimerPreset = UserTimerPresets::create($validated);

            return redirect()->route('presets')->with('success', "Timer preset '{$userTimerPreset->name}' created successfully.");
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to create timer preset: ' . $e->getMessage());
        }
    }

    /**
     * Show the user's timer presets edit page.
     */
    public function edit(string $id)
    {
        $userTimerPreset = UserTimerPresets::forUserSingle(Auth::id(), $id);
        if (!$userTimerPreset)
            return back();

        return Inertia::render('presets/CreateEditPreset', [
            'isCreateMode' => false,
            'timerPreset' => $userTimerPreset,
        ]);
    }

    /**
     * Update the authenticated user's timer presets.
     */
    public function update(UserTimerPresetRequest $request, string $id)
    {
        $validated = $request->validated();

        try {
            $userTimerPresets = UserTimerPresets::forUserSingle(Auth::id(), $id);
            if (!$userTimerPresets)
                throw new \Exception('Timer preset not found.');

            $userTimerPresets->update($validated);

            return redirect()->route('presets')->with('success', "Timer preset '{$userTimerPresets->name}' updated successfully.");
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update timer preset: ' . $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        try {
            $userTimerPresets = UserTimerPresets::forUserSingle(Auth::id(), $id);
            $userTimerPresets->delete();

            return redirect()->route('presets')->with('success', "Timer preset '{$userTimerPresets->name}' deleted successfully.");
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete timer preset: ' . $e->getMessage());
        }
    }
}
