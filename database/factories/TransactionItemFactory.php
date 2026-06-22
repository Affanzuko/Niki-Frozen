<?php

namespace Database\Factories;

use App\Models\TransactionItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TransactionItem>
 */
class TransactionItemFactory extends Factory
{
    protected $model = TransactionItem::class;

    public function definition(): array
    {
        $unitPrice = fake()->numberBetween(10000, 100000);
        $quantity = fake()->numberBetween(1, 10);
        $discountPercentage = fake()->randomElement([0, 10, 15, 20, 25]);
        $discountedPrice = $discountPercentage > 0 ? ($unitPrice * (100 - $discountPercentage) / 100) : null;
        $subtotal = ($discountedPrice ?? $unitPrice) * $quantity;

        return [
            'transaction_id' => null,       // Akan diset saat relasi
            'product_id' => null,           // Akan diset saat relasi
            'product_name' => fake()->words(3, true),
            'unit_price' => $unitPrice,
            'discounted_price' => $discountedPrice,
            'quantity' => $quantity,
            'subtotal' => $subtotal,
        ];
    }
}
