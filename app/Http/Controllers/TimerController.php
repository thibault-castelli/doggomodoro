<?php

namespace App\Http\Controllers;

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
