<?php

namespace Database\Factories;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Branch>
 */
class BranchFactory extends Factory
{
    protected $model = Branch::class;

    public function definition(): array
    {
        $cities = ['Jakarta', 'Bandung', 'Surabaya', 'Yogyakarta', 'Semarang', 'Medan', 'Makassar', 'Palembang'];
        $branches = ['Cabang Pusat', 'Cabang Utama', 'Cabang Pembantu', 'Cabang Satelit'];

        return [
            'name' => fake()->randomElement($branches) . ' ' . fake()->randomElement($cities),
            'address' => fake()->address(),
        ];
    }
}
