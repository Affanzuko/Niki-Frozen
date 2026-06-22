<?php

namespace Database\Factories;

use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductCategory>
 */
class ProductCategoryFactory extends Factory
{
    protected $model = ProductCategory::class;

    public function definition(): array
    {
        $categories = ['Daging', 'Sosis', 'Bakso', 'Nugget', 'Fish Product', 'Frozen Vegetable', 'Ready to Cook', 'Others'];

        return [
            'name' => fake()->randomElement($categories),
        ];
    }
}
