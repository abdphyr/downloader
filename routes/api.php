<?php

use Illuminate\Support\Facades\Route;



Route::prefix('auth')->name('auth.')->group(base_path('routes/api/auth/auth.php'));
Route::prefix('admin')->name('admin.')->group(base_path('routes/api/admin/admin.php'));
Route::prefix('web')->name('web.')->group(base_path('routes/api/web/web.php'));
Route::prefix('enum')->name('enum.')->group(base_path('routes/api/enum.php'));

