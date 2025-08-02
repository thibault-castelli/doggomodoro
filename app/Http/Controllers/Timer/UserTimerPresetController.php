<?php

namespace App\Http\Controllers\Timer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Timer\UserTimerPresetRequest;
use App\Models\UserTimerPreset;
use Exception;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class UserTimerPresetController extends Controller
{
    /**
     * Show user's timer presets page.
     */
    public function show()
    {
        return Inertia::render('presets/Presets', [
            'timerPresets' => UserTimerPreset::forUser(Auth::id()),
        ]);
    }

    /**
     * Show create timer preset page.
     */
    public function create()
    {
        return Inertia::render('presets/CreateEditPreset', [
            'isCreateMode' => true,
            'timerPreset' => UserTimerPreset::defaultPreset(),
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
            $userTimerPreset = UserTimerPreset::create($validated);

            return redirect()->route('presets')->with('success', "Timer preset '{$userTimerPreset->name}' created successfully.");
        } catch (Exception $e) {
            return back()->with('error', 'Failed to create timer preset: ' . $e->getMessage());
        }
    }

    /**
     * Show the user's timer presets edit page.
     */
    public function edit(string $id)
    {
        try {
            $userTimerPreset = UserTimerPreset::forUserSingle(Auth::id(), $id);

            return Inertia::render('presets/CreateEditPreset', [
                'isCreateMode' => false,
                'timerPreset' => $userTimerPreset,
            ]);
        } catch (Exception $e) {
            abort(404);
        }
    }

    /**
     * Update the authenticated user's timer presets.
     */
    public function update(UserTimerPresetRequest $request, string $id)
    {
        $validated = $request->validated();

        try {
            $userTimerPreset = UserTimerPreset::forUserSingle(Auth::id(), $id);
            $userTimerPreset->update($validated);

            return redirect()->route('presets')->with('success', "Timer preset '{$userTimerPreset->name}' updated successfully.");
        } catch (Exception $e) {
            return back()->with('error', 'Failed to update timer preset: ' . $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        try {
            if (UserTimerPreset::forUserCount(Auth::id()) <= 1) {
                throw new Exception('You cannot delete the only preset you have.');
            }

            $userTimerPreset = UserTimerPreset::forUserSingle(Auth::id(), $id);
            $userTimerPreset->delete();

            return redirect()->route('presets')->with('success', "Timer preset '{$userTimerPreset->name}' deleted successfully.");
        } catch (Exception $e) {
            return back()->with('error', 'Failed to delete timer preset: ' . $e->getMessage());
        }
    }
}
