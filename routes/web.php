<?php

use Illuminate\Support\Facades\Route;
use Laravel\Passport\Client;

Route::get('/', function () {
    return Client::all();
});
Route::get('unauthenticated', function () {
    return ['message' => 'Unuthenticated'];
})->name('login');
