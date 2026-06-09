<?php

namespace Database\Factories;

use App\Models\ProductUnit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductUnit>
 */
class ProductUnitFactory extends Factory
{
    protected $model = ProductUnit::class;

    public function definition(): array
    {
        $units = ['kg', 'liter', 'pcs', 'box', 'gram', 'ml', 'cup', 'pack'];

        return [
            'name' => fake()->randomElement($units),
        ];
    }
}
