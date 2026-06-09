<?php

namespace Database\Factories;

use App\Models\Stock;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Stock>
 */
class StockFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => null, // Akan diset saat relasi
            'branch_id' => null,  // Akan diset saat relasi
            'quantity' => fake()->numberBetween(10, 500),
        ];
    }
}
