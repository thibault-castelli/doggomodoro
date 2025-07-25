<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::post('/check-email', [RegisteredUserController::class, 'checkEmail'])->name('checkEmail');
});