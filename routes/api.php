<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Timer\UserTimerStatsController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::post('/check-email', [RegisteredUserController::class, 'checkEmail'])->name('checkEmail');
});

Route::middleware('auth:sanctum')->group(function() {
    Route::put('/timer-stats', [UserTimerStatsController::class, 'update'])->name('update.timerStats');
});