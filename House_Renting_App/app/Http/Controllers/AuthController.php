<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\LoginResource;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    //

    protected $authService;
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request)
    {
        $user = $this->authService->register($request->validated());
        return $this->success(LoginResource::make($user), "User Created Successfully .", 201);
    }
    public function login(LoginRequest $request)
    {
        $message = "User Logged In Successfully .";
        $user = $this->authService->login($request->validated());
        if (!$user) {
            $message = "Invalid Credntials";
            return $this->success(null, $message, 400);
        }
        return $this->success(LoginResource::make($user), $message, 200);
    }
}
