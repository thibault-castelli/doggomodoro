<?php

namespace App\Http\Controllers;

use App\Models\UserTimerSettings;
use Illuminate\Support\Facades\Auth;

class TimerController extends Controller
{
    /**
     * Show the timer page.
     */
    public function index()
    {
        $user_timer_settings = UserTimerSettings::forUser(Auth::id());

        return inertia('Timer', [
            'timerSettings' => $user_timer_settings,
        ]);
    }
}
