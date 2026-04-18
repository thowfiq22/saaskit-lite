<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * @var list<array{name: string, sku: string, description: string, price: float, currency: string, stock: int, is_active: bool}>
     */
    private array $products = [
        [
            'name' => 'Starter Analytics',
            'sku' => 'SKU-ANALYTICS-001',
            'description' => 'Analytics add-on for product metrics and engagement trends.',
            'price' => 49.00,
            'currency' => 'USD',
            'stock' => 40,
            'is_active' => true,
        ],
        [
            'name' => 'Billing Console',
            'sku' => 'SKU-BILLING-002',
            'description' => 'Back-office module for invoices, plans, and team billing workflows.',
            'price' => 79.00,
            'currency' => 'USD',
            'stock' => 25,
            'is_active' => true,
        ],
        [
            'name' => 'Team Workspace',
            'sku' => 'SKU-WORKSPACE-003',
            'description' => 'Workspace package for teams managing multiple client projects.',
            'price' => 99.00,
            'currency' => 'USD',
            'stock' => 18,
            'is_active' => true,
        ],
        [
            'name' => 'Usage Monitor',
            'sku' => 'SKU-USAGE-004',
            'description' => 'Operational monitoring module for quota usage and account health.',
            'price' => 59.00,
            'currency' => 'USD',
            'stock' => 30,
            'is_active' => true,
        ],
        [
            'name' => 'Support Inbox',
            'sku' => 'SKU-SUPPORT-005',
            'description' => 'Shared inbox workflow for handling support operations.',
            'price' => 39.00,
            'currency' => 'USD',
            'stock' => 45,
            'is_active' => true,
        ],
        [
            'name' => 'Audit Timeline',
            'sku' => 'SKU-AUDIT-006',
            'description' => 'Activity timeline for compliance-sensitive actions and history review.',
            'price' => 69.00,
            'currency' => 'USD',
            'stock' => 12,
            'is_active' => false,
        ],
    ];

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::query()->where('email', 'admin@example.com')->firstOrFail();

        foreach ($this->products as $payload) {
            Product::query()->updateOrCreate(
                ['sku' => $payload['sku']],
                [...$payload, 'created_by' => $admin->id]
            );
        }
    }
}
