<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_a_product(): void
    {
        $admin = User::factory()->admin()->create();

        Sanctum::actingAs($admin);

        $response = $this->postJson('/api/v1/products', [
            'name' => 'Growth Dashboard',
            'description' => 'Module for growth reporting.',
            'sku' => 'SKU-GROWTH-100',
            'price' => 129.99,
            'currency' => 'usd',
            'stock' => 10,
            'is_active' => true,
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('data.product.name', 'Growth Dashboard')
            ->assertJsonPath('data.product.currency', 'USD');

        $this->assertDatabaseHas('products', [
            'sku' => 'SKU-GROWTH-100',
            'created_by' => $admin->id,
        ]);
    }

    public function test_regular_user_cannot_create_a_product(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $response = $this->postJson('/api/v1/products', [
            'name' => 'Restricted Product',
            'sku' => 'SKU-LOCKED-200',
            'price' => 29.99,
        ]);

        $response
            ->assertForbidden()
            ->assertJsonPath('success', false);
    }

    public function test_products_can_be_filtered_and_paginated(): void
    {
        $admin = User::factory()->admin()->create();
        $viewer = User::factory()->create();

        Product::factory()->create([
            'name' => 'Desk Analytics',
            'sku' => 'SKU-DESK-001',
            'is_active' => true,
            'created_by' => $admin->id,
        ]);

        Product::factory()->create([
            'name' => 'Desk Archive',
            'sku' => 'SKU-DESK-002',
            'is_active' => false,
            'created_by' => $admin->id,
        ]);

        Product::factory()->create([
            'name' => 'Mobile Console',
            'sku' => 'SKU-MOBILE-003',
            'is_active' => true,
            'created_by' => $admin->id,
        ]);

        Sanctum::actingAs($viewer);

        $response = $this->getJson('/api/v1/products?search=Desk&is_active=1&per_page=1');

        $response
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('meta.pagination.per_page', 1)
            ->assertJsonPath('data.0.name', 'Desk Analytics');
    }

    public function test_product_update_requires_at_least_one_field(): void
    {
        $admin = User::factory()->admin()->create();
        $product = Product::factory()->create([
            'created_by' => $admin->id,
        ]);

        Sanctum::actingAs($admin);

        $response = $this->patchJson("/api/v1/products/{$product->id}", []);

        $response
            ->assertUnprocessable()
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', 'The given data was invalid.')
            ->assertJsonStructure([
                'errors' => [
                    'payload',
                ],
            ]);
    }
}
