<?php

namespace App\Http\Controllers\Timer;

use App\Models\UserTimerStats;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Inertia\Inertia;

class UserTimerStatsController extends Controller
{
    public function show()
    {
        return Inertia::render('Dashboard', [
            'timerStats' => UserTimerStats::forUser(Auth::id())
        ]);
    }
}
