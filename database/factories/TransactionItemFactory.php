<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\TransactionItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TransactionItem>
 */
class TransactionItemFactory extends Factory
{
    protected $model = TransactionItem::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // $product = Product::inRandomOrder()->first() ?? Product::factory()->create();
        // $quantity = $this->faker->numberBetween(1, 5);
        // $unitPrice = $product->price;

        // return [
        //     'product_id' => $product->id,
        //     'product_name' => $product->name,
        //     'unit_price' => $unitPrice,
        //     'discounted_price' => null,
        //     'quantity' => $quantity,
        //     'subtotal' => $unitPrice * $quantity,
        // ];
    }
}
