<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DashboardApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_includes_user_counts(): void
    {
        $admin = User::factory()->admin()->create();
        User::factory()->count(2)->create();

        Sanctum::actingAs($admin);

        $response = $this->getJson('/api/v1/dashboard');

        $response
            ->assertOk()
            ->assertJsonPath('data.stats.role', 'admin')
            ->assertJsonPath('data.stats.users_total', 3)
            ->assertJsonPath('data.stats.admins_total', 1);
    }

    public function test_user_dashboard_hides_admin_only_counts(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/v1/dashboard');

        $response
            ->assertOk()
            ->assertJsonPath('data.stats.role', 'user')
            ->assertJsonMissingPath('data.stats.users_total')
            ->assertJsonMissingPath('data.stats.admins_total');
    }
}
