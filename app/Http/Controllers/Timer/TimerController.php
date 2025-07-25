<?php

namespace App\Http\Controllers\Timer;

use App\Http\Controllers\Controller;
use App\Models\UserTimerPreset;
use Illuminate\Support\Facades\Auth;

class TimerController extends Controller
{
    /**
     * Show the timer page.
     */
    public function index()
    {
        $userTimerPresets = UserTimerPreset::forUser(Auth::id());

        return inertia('Timer', [
            'timerPresets' => $userTimerPresets,
        ]);
    }
}
