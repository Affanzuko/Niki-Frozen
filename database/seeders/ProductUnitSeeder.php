<?php

namespace Database\Seeders;

use App\Models\ProductUnit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            ['name' => 'kg'],
            ['name' => 'liter'],
            ['name' => 'pcs'],
            ['name' => 'box'],
            ['name' => 'gram'],
            ['name' => 'ml'],
            ['name' => 'cup'],
            ['name' => 'pack'],
        ];

        foreach ($units as $unit) {
            ProductUnit::create($unit);
        }
    }
}
