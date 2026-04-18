<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class SeedersTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeders_are_idempotent(): void
    {
        Artisan::call('db:seed');
        Artisan::call('db:seed');

        $this->assertSame(4, User::query()->count());
        $this->assertSame(6, Product::query()->count());
        $this->assertDatabaseHas('users', ['email' => 'admin@example.com']);
        $this->assertDatabaseHas('products', ['sku' => 'SKU-ANALYTICS-001']);
    }
}
