<?php

namespace Database\Factories;

use App\Models\CashierSession;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CashierSession>
 */
class CashierSessionFactory extends Factory
{
    protected $model = CashierSession::class;

    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('-30 days', 'now');
        $startedAt = $startDate;
        $endedAt = fake()->randomElement([
            $startDate->modify('+8 hours'), // Sesi berakhir normal
            null, // Sesi masih berjalan
        ]);

        return [
            'user_id' => null,           // Akan diset saat relasi
            'branch_id' => null,         // Akan diset saat relasi
            'shift' => fake()->randomElement(['morning', 'evening']),
            'date' => $startDate->format('Y-m-d'),
            'started_at' => $startedAt,
            'ended_at' => $endedAt,
        ];
    }

    /**
     * Indicate that the cashier session is still active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'ended_at' => null,
        ]);
    }

    /**
     * Indicate that the cashier session is closed.
     */
    public function closed(): static
    {
        return $this->state(fn (array $attributes) => [
            'ended_at' => fake()->dateTime(),
        ]);
    }
}
