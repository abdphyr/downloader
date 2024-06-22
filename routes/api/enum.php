<?php

use App\Http\Controllers\EnumController;
use Illuminate\Support\Facades\Route;


Route::get('subject-status', [EnumController::class, 'subjectStatus'])->name('subject-status');
Route::get('education-degree', [EnumController::class, 'educationDegree'])->name('education-degree');