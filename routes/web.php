<?php

use App\Http\Controllers\TimerController;
use App\Http\Controllers\UserTimerPresetController;
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

    Route::get('/presets', [UserTimerPresetController::class, 'show'])->name('presets');
    Route::get('/presets/create', [UserTimerPresetController::class, 'create'])->name('presets.create');
    Route::get('/presets/{id}', [UserTimerPresetController::class, 'edit'])->name('presets.edit');
    Route::post('/presets', [UserTimerPresetController::class, 'store'])->name('presets.store');
    Route::put('/presets/{id}', [UserTimerPresetController::class, 'update'])->name('presets.update');
    Route::delete('/presets/{id}', [UserTimerPresetController::class, 'destroy'])->name('presets.delete');
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
