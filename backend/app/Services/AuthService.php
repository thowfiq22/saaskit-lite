<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function __construct(private readonly UserRepositoryInterface $users)
    {
    }

    /**
     * @return array{user: User, token: string}
     */
    public function register(array $payload): array
    {
        $user = $this->users->create([
            'name' => $payload['name'],
            'email' => $payload['email'],
            'password' => $payload['password'],
            'role' => UserRole::USER,
        ]);

        return [
            'user' => $user,
            'token' => $user->createToken('auth_token')->plainTextToken,
        ];
    }

    /**
     * @return array{user: User, token: string}
     */
    public function login(array $credentials): array
    {
        $user = $this->users->findByEmail($credentials['email']);

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return [
            'user' => $user,
            'token' => $user->createToken('auth_token')->plainTextToken,
        ];
    }

    public function logout(User $user): void
    {
        $user->currentAccessToken()?->delete();
    }
}
