<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'item_code' => 'IC-1000'.rand(10, 500), // after php 7.1 no diff rand or mt_rand. (rand alias mt_rand)
            'description' => 'Name of Product'.rand(10, 500),
            'unit_price' => mt_rand(100, 1000),
        ];
    }
}
