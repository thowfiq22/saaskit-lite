<?php

namespace App\Services;

use App\Models\Product;
use App\Models\User;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductService
{
    public function __construct(private readonly ProductRepositoryInterface $products)
    {
    }

    public function list(array $filters): LengthAwarePaginator
    {
        return $this->products->paginateWithFilters($filters);
    }

    public function create(array $payload, User $actor): Product
    {
        $payload['created_by'] = $actor->id;

        return $this->products->create($payload);
    }

    public function update(Product $product, array $payload): Product
    {
        return $this->products->update($product, $payload);
    }

    public function delete(Product $product): void
    {
        $this->products->delete($product);
    }
}
