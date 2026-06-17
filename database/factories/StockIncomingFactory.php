<?php

namespace Database\Factories;

use App\Models\StockIncoming;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StockIncoming>
 */
class StockIncomingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $quantity = fake()->numberBetween(50, 500);

        return [
            'product_id'       => null,  // Akan diset saat relasi
            'branch_id'        => null,  // Akan diset saat relasi
            'user_id'          => null,  // Akan diset saat relasi
            'quantity'         => $quantity,
            'current_quantity' => $quantity, // Batch baru, sisa = jumlah awal
            'expiry_date'      => fake()->dateTimeBetween(now()->addMonths(1), now()->addMonths(12)),
        ];
    }
}
