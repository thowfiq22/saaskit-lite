<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\PersonalAccessToken;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_and_receive_token(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'Jane Builder',
            'email' => 'jane@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.user.email', 'jane@example.com')
            ->assertJsonPath('data.user.role', UserRole::USER->value)
            ->assertJsonStructure([
                'data' => [
                    'user',
                    'access_token',
                    'token_type',
                ],
            ]);
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        User::factory()->create([
            'email' => 'login@example.com',
            'password' => 'password',
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'login@example.com',
            'password' => 'password',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.user.email', 'login@example.com')
            ->assertJsonPath('data.token_type', 'Bearer');
    }

    public function test_auth_requests_normalize_email_input(): void
    {
        User::factory()->create([
            'email' => 'normalized@example.com',
            'password' => 'password',
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => '  NORMALIZED@example.com  ',
            'password' => 'password',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('data.user.email', 'normalized@example.com');
    }

    public function test_authenticated_user_can_logout_current_token(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test_token')->plainTextToken;

        $response = $this
            ->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/v1/auth/logout');

        $response
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Logout successful.');

        $this->assertSame(0, PersonalAccessToken::query()->count());
    }
}
