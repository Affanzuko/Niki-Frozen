<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductUnit;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $branches = Branch::all();
        $categories = ProductCategory::all();
        $units = ProductUnit::all();

        $topProducts = [
            ['barcode' => '8991234567801', 'name' => 'Fiesta Chicken Nugget 500g', 'price' => 45500],
            ['barcode' => '8991234567802', 'name' => 'Kanzler Sosis Bakar Keju 500g', 'price' => 52000],
            ['barcode' => '8991234567803', 'name' => 'Golden Farm French Fries 1kg', 'price' => 38000],
            ['barcode' => '8991234567804', 'name' => 'Cedea Dumpling Keju 500g', 'price' => 32500],
        ];

        // Create top products for each branch
        foreach ($branches as $branch) {
            foreach ($topProducts as $item) {
                Product::create([
                    'branch_id' => $branch->id,
                    'product_categories_id' => $categories->random()->id,
                    'product_units_id' => $units->random()->id,
                    'barcode' => $item['barcode'] . '-' . $branch->id,
                    'name' => $item['name'],
                    'price' => $item['price'],
                ]);
            }
        }

        // Create random products
        foreach ($branches as $branch) {
            Product::factory(46)
                ->create([
                    'branch_id' => $branch->id,
                    'product_categories_id' => $categories->random()->id,
                    'product_units_id' => $units->random()->id,
                ]);
        }
    }
}
