<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(private readonly AuthService $authService)
    {
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $payload = $this->authService->register($request->validated());

        return ApiResponse::success('Account created successfully.', [
            'user' => UserResource::make($payload['user']),
            'access_token' => $payload['token'],
            'token_type' => 'Bearer',
        ], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $payload = $this->authService->login($request->validated());

        return ApiResponse::success('Login successful.', [
            'user' => UserResource::make($payload['user']),
            'access_token' => $payload['token'],
            'token_type' => 'Bearer',
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request->user());

        return ApiResponse::success('Logout successful.');
    }

    public function me(Request $request): JsonResponse
    {
        return ApiResponse::success('Authenticated user fetched successfully.', [
            'user' => UserResource::make($request->user()),
        ]);
    }
}
