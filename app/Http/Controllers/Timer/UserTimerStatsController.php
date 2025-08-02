<?php

namespace App\Http\Controllers\Timer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Timer\UserTimerStatsRequest;
use App\Models\UserTimerStats;
use Auth;
use Exception;
use Inertia\Inertia;

class UserTimerStatsController extends Controller
{
    public function show()
    {
        return Inertia::render('Dashboard', [
            'timerStats' => UserTimerStats::forUser(Auth::id()),
        ]);
    }

    public function update(UserTimerStatsRequest $request)
    {
        $validated = $request->validated();

        try {
            $userTimerStats = UserTimerStats::forUser(Auth::id());
            $validated['total_work_time'] += $userTimerStats->total_work_time;
            $validated['total_break_time'] += $userTimerStats->total_break_time;
            $validated['total_rounds_completed'] += $userTimerStats->total_rounds_completed;
            $validated['total_sessions_completed'] = $userTimerStats->total_sessions_completed + 1;
            $userTimerStats->update($validated);

            return response()->json();
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to update timer statistics: ' . $e->getMessage()], 500);
        }
    }
}
