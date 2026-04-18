<?php

namespace App\Repositories\Eloquent;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductRepository implements ProductRepositoryInterface
{
    public function paginateWithFilters(array $filters): LengthAwarePaginator
    {
        $query = Product::query()->with('creator:id,name,email');

        if (! empty($filters['search'])) {
            $search = $filters['search'];

            $query->where(function ($builder) use ($search) {
                $builder
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if (array_key_exists('is_active', $filters) && $filters['is_active'] !== null) {
            $query->where('is_active', (bool) $filters['is_active']);
        }

        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortDirection = $filters['sort_direction'] ?? 'desc';

        return $query
            ->orderBy($sortBy, $sortDirection)
            ->paginate($filters['per_page'] ?? 10)
            ->withQueryString();
    }

    public function create(array $attributes): Product
    {
        return Product::query()
            ->create($attributes)
            ->load('creator:id,name,email');
    }

    public function update(Product $product, array $attributes): Product
    {
        $product->update($attributes);

        return $product->refresh()->load('creator:id,name,email');
    }

    public function delete(Product $product): void
    {
        $product->delete();
    }
}
