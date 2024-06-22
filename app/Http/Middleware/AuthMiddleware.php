<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Auth\Middleware\Authenticate;

class AuthMiddleware extends Authenticate
{
    protected function redirectTo(Request $request): ?string
    {
        if (! $request->expectsJson()) {
            return route('auth.unauthenticated');
        }
    }
}
