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
        $date = fake()->dateTimeBetween('-30 days', 'now');
        $dateStr = $date->format('d-m-Y');

        $shift = fake()->randomElement(['pagi', 'siang']);

        // Morning shift: 07:00-14:00, Evening shift: 14:00-21:00
        if ($shift === 'pagi') {
            $hour = fake()->numberBetween(7, 9);
        } else {
            $hour = fake()->numberBetween(14, 16);
        }

        $startedAt = \Carbon\Carbon::parse($dateStr)->setHour($hour)->setMinute(0)->setSecond(0);

        // 70% chance session is closed
        $endedAt = fake()->boolean(70)
            ? (clone $startedAt)->addHours(fake()->numberBetween(6, 8))
            : null;

        return [
            'user_id' => null,           // Akan diset saat relasi
            'branch_id' => null,         // Akan diset saat relasi
            'shift' => $shift,
            'date' => $dateStr,
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
        return $this->state(function (array $attributes) {
            $startedAt = \Carbon\Carbon::parse($attributes['started_at']);
            return [
                'ended_at' => (clone $startedAt)->addHours(fake()->numberBetween(6, 8)),
            ];
        });
    }
}
