<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ProductIndexRequest;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ProductService;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function __construct(private readonly ProductService $productService)
    {
    }

    public function index(ProductIndexRequest $request): JsonResponse
    {
        $products = $this->productService->list($request->validated());

        return ApiResponse::paginated(
            $products,
            ProductResource::collection($products)->resolve(),
            'Products fetched successfully.'
        );
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $product = $this->productService->create([
            ...$validated,
            'currency' => $validated['currency'] ?? 'USD',
            'stock' => $validated['stock'] ?? 0,
            'is_active' => $validated['is_active'] ?? true,
        ], $request->user());

        return ApiResponse::success('Product created successfully.', [
            'product' => ProductResource::make($product),
        ], 201);
    }

    public function show(Product $product): JsonResponse
    {
        return ApiResponse::success('Product fetched successfully.', [
            'product' => ProductResource::make($product->load('creator:id,name,email')),
        ]);
    }

    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        $product = $this->productService->update($product, $request->validated());

        return ApiResponse::success('Product updated successfully.', [
            'product' => ProductResource::make($product),
        ]);
    }

    public function destroy(Product $product): JsonResponse
    {
        $this->productService->delete($product);

        return ApiResponse::success('Product deleted successfully.');
    }
}
