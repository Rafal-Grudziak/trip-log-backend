<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\EnumController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/register', [RegisterController::class, 'register']);
    Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('/users')->name('users.')->group(function () {
        Route::get('/me', [UserController::class, 'show'])->name('me');
        Route::patch('/me/update-password', [UserController::class, 'updatePassword'])->name('update_password');
    });

    Route::prefix('profiles')->name('profiles.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::put('/update', [ProfileController::class, 'update'])->name('update');
    });

    Route::prefix('enums')->name('enums.')->group(function () {
        Route::get('/travel-preferences', [EnumController::class, 'getTravelPreferences'])->name('travel_preferences');
    });

});



