<?php

namespace App\Repositories\Contracts;

use Illuminate\Support\Collection;

interface DashboardRepositoryInterface
{
    public function countUsers(): int;

    public function countAdmins(): int;

    public function countProducts(): int;

    public function countActiveProducts(): int;

    public function latestProducts(int $limit = 5): Collection;
}
