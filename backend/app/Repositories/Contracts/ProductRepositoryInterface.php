<?php

namespace App\Repositories\Contracts;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ProductRepositoryInterface
{
    public function paginateWithFilters(array $filters): LengthAwarePaginator;

    public function create(array $attributes): Product;

    public function update(Product $product, array $attributes): Product;

    public function delete(Product $product): void;
}
