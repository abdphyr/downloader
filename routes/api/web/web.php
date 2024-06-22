<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\UserController;
use App\Partials\File\FileRoutes;
use Illuminate\Support\Facades\Route;


Route::apiResource('category', CategoryController::class);
Route::apiResource('user', UserController::class);
FileRoutes::filesResource('user', UserController::class);
Route::apiResource('subject', SubjectController::class);
FileRoutes::filesResource('subject', SubjectController::class);
// Route::group(['middleware' => 'auth:api'], function () {
// });
