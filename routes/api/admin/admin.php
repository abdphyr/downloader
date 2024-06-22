<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\SubjectTypeController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:api'], function () {
    Route::apiResource('category', CategoryController::class);
    Route::apiResource('language', LanguageController::class);
    Route::get('category-all', [CategoryController::class, 'all'])->name('category.all');
});
Route::apiResource('subject_type', SubjectTypeController::class);
