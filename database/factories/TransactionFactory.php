<?php

namespace Database\Factories;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition(): array
    {
        // Men-generate format TRX-YYYYMMDD-XXX
        // $date = $this->faker->dateTimeThisYear();
        // $code = 'TRX-' . $date->format('Ymd') . '-' . str_pad($this->faker->unique()->numberBetween(1, 9999), 4, '0', STR_PAD_LEFT);

        // return [
        //     // 'cashier_session_id' => CashierSession::inRandomOrder()->value('id') ?? 1, // Ambil ID acak
        //     // 'branch_id'          => Branch::inRandomOrder()->value('id') ?? 1,
        //     'transaction_code'   => $code,
        //     'payment_method'     => $this->faker->randomElement(['cash', 'qris']),
        //     'total_price'        => 0,
        //     'status'             => $this->faker->randomElement(['success', 'success', 'pending', 'failed']), // Perbanyak success
        //     'created_at'         => $date,
        //     'updated_at'         => $date,
        // ];
    }
}
