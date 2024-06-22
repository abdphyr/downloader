<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\UserService;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use Psr\Http\Message\ServerRequestInterface;

class AuthController extends Controller
{
    public function __construct(
        protected UserService $userService,
        protected AccessTokenController $accessTokenController
    ) {
    }

    public function register(RegisterRequest $registerRequest)
    {
        return $this->userService->create($registerRequest->validated());
    }

    public function login(ServerRequestInterface $request, LoginRequest $loginRequest)
    {
        $passportToken = $this->accessTokenController->issueToken($request)->getContent();
        return json_decode($passportToken);
    }

    public function profile()
    {
        return auth()->user();
    }
}
