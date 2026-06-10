<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Stock;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create stock for each product in its own branch only
        // (products are already assigned to a specific branch)
        $products = Product::all();

        foreach ($products as $product) {
            Stock::create([
                'product_id' => $product->id,
                'branch_id' => $product->branch_id,
                'quantity' => fake()->numberBetween(50, 200),
            ]);
        }
    }
}
