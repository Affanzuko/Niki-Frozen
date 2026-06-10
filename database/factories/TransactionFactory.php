<?php

namespace Database\Factories;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Transaction>
 */
class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition(): array
    {
        $statuses = ['pending', 'success', 'failed', 'rollback'];

        return [
            'cashier_session_id' => null,      // Akan diset saat relasi
            'branch_id' => null,               // Akan diset saat relasi
            'transaction_code' => 'TRX' . fake()->unique()->numerify('##########'),
            'payment_method' => fake()->randomElement(['cash', 'qris']),
            'total_price' => fake()->numberBetween(50000, 500000),
            'status' => fake()->randomElement($statuses),
            'snap_token' => fake()->optional(0.3)->sha256(),
            'midtrans_transaction_id' => fake()->optional(0.3)->numerify('###############'),
        ];
    }

    /**
     * Indicate that the transaction is paid.
     */
    public function success(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'success',
        ]);
    }

    /**
     * Indicate that the transaction failed.
     */
    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'failed',
        ]);
    }

    /**
     * Indicate that the transaction is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
        ]);
    }
}
