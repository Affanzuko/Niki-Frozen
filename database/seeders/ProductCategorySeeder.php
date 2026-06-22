<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Daging'],
            ['name' => 'Sosis'],
            ['name' => 'Bakso'],
            ['name' => 'Nugget'],
            ['name' => 'Ikan'],
            ['name' => 'Sayur'],
            ['name' => 'Instant'],
            ['name' => 'Lainnya'],
        ];

        foreach ($categories as $category) {
            ProductCategory::create($category);
        }
    }
}
