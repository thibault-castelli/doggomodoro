<?php

use App\Http\Controllers\Timer\TimerController;
use App\Http\Controllers\Timer\UserTimerPresetController;
use App\Http\Controllers\Timer\UserTimerStatsController;
use Illuminate\Support\Facades\Route;

Route::get('/', [TimerController::class, 'index'])->name('timer');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [UserTimerStatsController::class, 'show'])->name('dashboard');

    Route::get('/presets', [UserTimerPresetController::class, 'show'])->name('presets');
    Route::get('/presets/create', [UserTimerPresetController::class, 'create'])->name('presets.create');
    Route::get('/presets/{id}', [UserTimerPresetController::class, 'edit'])->name('presets.edit');
    Route::post('/presets', [UserTimerPresetController::class, 'store'])->name('presets.store');
    Route::put('/presets/{id}', [UserTimerPresetController::class, 'update'])->name('presets.update');
    Route::delete('/presets/{id}', [UserTimerPresetController::class, 'destroy'])->name('presets.delete');
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
require __DIR__ . '/api.php';
