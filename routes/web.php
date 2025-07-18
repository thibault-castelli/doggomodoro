<?php

use App\Http\Controllers\TimerController;
use App\Http\Controllers\UserTimerPresetsController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('/timer', [TimerController::class, 'index'])->name('timer');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('/presets', [UserTimerPresetsController::class, 'edit'])->name('presets');
    Route::put('/presets/{id}', [UserTimerPresetsController::class, 'update'])->name('presets.update');
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
