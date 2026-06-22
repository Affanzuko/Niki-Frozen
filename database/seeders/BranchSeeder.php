<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $branches = [
            [
                'name' => 'NikiFrozen - Condongcatur',
                'address' => 'Condongcatur, Depok, Sleman, Yogyakarta',
            ],
            [
                'name' => 'NikiFrozen - Umbulharjo',
                'address' => 'Umbulharjo, Yogyakarta',
            ],
            [
                'name' => 'NikiFrozen - Seturan',
                'address' => 'Seturan, Depok, Sleman, Yogyakarta',
            ],
        ];

        foreach ($branches as $branch) {
            Branch::create($branch);
        }
    }
}
