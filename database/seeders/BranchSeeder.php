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
                'name' => 'Niki Frozen - Condongcatur',
                'address' => 'Condongcatur, Depok, Sleman, Yogyakarta',
            ],
            [
                'name' => 'Niki Frozen - Umbulharjo',
                'address' => 'Umbulharjo, Yogyakarta',
            ],
            [
                'name' => 'Niki Frozen - Imogiri',
                'address' => 'Imogiri, Sleman, Yogyakarta',
            ],
        ];

        foreach ($branches as $branch) {
            Branch::create($branch);
        }
    }
}
