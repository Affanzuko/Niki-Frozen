<?php

namespace Database\Factories;

use App\Models\Promotion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Promotion>
 */
class PromotionFactory extends Factory
{
    protected $model = Promotion::class;

    public function definition(): array
    {
        return [
            'product_id' => null,          // Akan diset saat relasi
            'stock_incoming_id' => null,   // Akan diset saat relasi
            'branch_id' => null,           // Akan diset saat relasi
            'created_by' => null,          // Akan diset saat relasi
            'discount_percentage' => fake()->randomElement([5, 10, 15, 20, 25, 30, 50]),
            'valid_until' => fake()->dateTimeBetween('now', '+3 months'),
            'is_active' => fake()->randomElement([true, false]),
            'quota' => fake()->numberBetween(1, 100),
        ];
    }

    /**
     * Indicate that the promotion is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the promotion is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
