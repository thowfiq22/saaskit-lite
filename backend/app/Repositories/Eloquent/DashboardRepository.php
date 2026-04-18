<?php

namespace App\Repositories\Eloquent;

use App\Enums\UserRole;
use App\Models\Product;
use App\Models\User;
use App\Repositories\Contracts\DashboardRepositoryInterface;
use Illuminate\Support\Collection;

class DashboardRepository implements DashboardRepositoryInterface
{
    public function countUsers(): int
    {
        return User::query()->count();
    }

    public function countAdmins(): int
    {
        return User::query()
            ->where('role', UserRole::ADMIN->value)
            ->count();
    }

    public function countProducts(): int
    {
        return Product::query()->count();
    }

    public function countActiveProducts(): int
    {
        return Product::query()
            ->where('is_active', true)
            ->count();
    }

    public function latestProducts(int $limit = 5): Collection
    {
        return Product::query()
            ->with('creator:id,name,email')
            ->latest()
            ->limit($limit)
            ->get();
    }
}
