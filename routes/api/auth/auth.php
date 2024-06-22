<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Partials\Static\StaticRoute;
use Illuminate\Support\Facades\Route;


// Route::get('unauthenticated', fn () => message('Unauthenticated', 401))->name('unauthenticated');

// Route::apiResource('users', UserController::class);
// StaticRoute::assetsResource('users', UserController::class);

Route::post('login', [AuthController::class, 'login'])->name('login');
Route::get('profile', [AuthController::class, 'profile'])->name('profile')->middleware('auth:api');
Route::post('register', [AuthController::class, 'register'])->name('register');