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
        return [
            'user_id' => null,           // Akan diset saat relasi
            'branch_id' => null,         // Akan diset saat relasi
            'shift' => fake()->randomElement(['pagi', 'siang']),
            'date' => fake()->dateTimeBetween('-30 days', 'now')->format('Y-m-d'),
            'started_at' => function (array $attributes) {
                $startHour = ($attributes['shift'] === 'pagi') ? 7 : 14;
                return \Carbon\Carbon::parse($attributes['date'])->setHour($startHour)->setMinute(0)->setSecond(0);
            },
            'ended_at' => function (array $attributes) {
                // 70% chance session is closed
                if (fake()->boolean(70)) {
                    $endHour = ($attributes['shift'] === 'pagi') ? 14 : 21;
                    return \Carbon\Carbon::parse($attributes['date'])->setHour($endHour)->setMinute(0)->setSecond(0);
                }
                return null;
            },
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
}
