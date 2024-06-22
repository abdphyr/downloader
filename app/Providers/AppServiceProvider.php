<?php

namespace App\Providers;

use App\Http\Resources\CategoryResource;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Passport::enablePasswordGrant();
        CategoryResource::withoutWrapping();
    }
}
