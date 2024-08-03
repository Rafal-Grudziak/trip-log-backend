<?php

use App\Http\Controllers\Api\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;


Route::controller(AuthController::class)->group(function () {
    Route::post('/login', 'login')->name('login');
    Route::post('/register', 'register')->name('register');
    Route::post('/logout', 'logout')->middleware('auth:sanctum')->name('logout');
});
//Route::post('login', [AuthController::class, 'login']);
//Route::post('register', [AuthController::class, 'register']);
Route::controller(ProfileController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [ProfileController::class, 'profile']);
//    Route::post('/logout', [AuthController::class, 'logout']);
});
