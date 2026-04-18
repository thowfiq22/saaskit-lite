<?php

namespace App\Services;

use App\Http\Resources\ProductResource;
use App\Models\User;
use App\Repositories\Contracts\DashboardRepositoryInterface;

class DashboardService
{
    public function __construct(private readonly DashboardRepositoryInterface $dashboard)
    {
    }

    /**
     * @return array<string, mixed>
     */
    public function overview(User $user): array
    {
        $baseStats = [
            'role' => $user->role?->value ?? $user->role,
            'products_total' => $this->dashboard->countProducts(),
            'active_products_total' => $this->dashboard->countActiveProducts(),
            'latest_products' => ProductResource::collection(
                $this->dashboard->latestProducts()
            )->resolve(),
        ];

        if (! $user->isAdmin()) {
            return $baseStats;
        }

        return [
            ...$baseStats,
            'users_total' => $this->dashboard->countUsers(),
            'admins_total' => $this->dashboard->countAdmins(),
        ];
    }
}
