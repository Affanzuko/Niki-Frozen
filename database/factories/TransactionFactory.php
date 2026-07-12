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

    private static $counter = 1;

    public function definition(): array
    {
        $statuses = ['pending', 'success', 'failed', 'return'];
        $date = now()->format('dmY');
        $sequence = str_pad(self::$counter++, 3, '0', STR_PAD_LEFT);

        return [
            'cashier_session_id' => null,      // Akan diset saat relasi
            'branch_id' => null,               // Akan diset saat relasi
            'transaction_code' => 'TRX-' . $date . '-' . $sequence,
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
