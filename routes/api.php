<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Timer\DailySessionCountController;
use App\Http\Controllers\Timer\UserTimerStatsController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::post('/check-email', [RegisteredUserController::class, 'checkEmail'])->name('checkEmail');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::put('/timer-stats', [UserTimerStatsController::class, 'update'])->name('timerStats.update');

    Route::get('/daily-session-count/{from}/{to?}', [DailySessionCountController::class, 'get'])->name('dailySessionCount');
    Route::post('daily-session-count', [DailySessionCountController::class, 'createOrUpdate'])->name('dailySessionCount.createOrUpdate');
});
