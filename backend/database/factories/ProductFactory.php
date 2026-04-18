<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);

        return [
            'name' => Str::title($name),
            'description' => fake()->sentence(12),
            'sku' => strtoupper(fake()->unique()->bothify('SKU-####-??')),
            'price' => fake()->randomFloat(2, 19, 999),
            'currency' => 'USD',
            'stock' => fake()->numberBetween(0, 250),
            'is_active' => fake()->boolean(80),
            'created_by' => User::factory(),
        ];
    }
}
