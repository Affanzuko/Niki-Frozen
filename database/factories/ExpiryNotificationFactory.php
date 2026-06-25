<?php

namespace Database\Factories;

use App\Models\ExpiryNotification;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ExpiryNotification>
 */
class ExpiryNotificationFactory extends Factory
{
    protected $model = ExpiryNotification::class;

    public function definition(): array
    {
        $messages = [
            'Produk akan kadaluarsa dalam 7 hari',
            'Peringatan: Stok produk akan kadaluarsa segera',
            'Produk kadaluarsa: Segera proses penghapusan stok',
            'Alert: Tanggal kadaluarsa sudah dekat',
            'Stok produk mendekati tanggal kadaluarsa',
        ];

        return [
            'stock_incoming_id' => null,  // Akan diset saat relasi
            'branch_id' => null,          // Akan diset saat relasi
            'message' => fake()->randomElement($messages),
            'is_read' => fake()->randomElement([true, false]),
        ];
    }

    /**
     * Indicate that the notification is read.
     */
    public function read(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_read' => true,
        ]);
    }

    /**
     * Indicate that the notification is unread.
     */
    public function unread(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_read' => false,
        ]);
    }
}
