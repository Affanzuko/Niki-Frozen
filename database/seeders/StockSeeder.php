<?php

namespace Database\Seeders;

use App\Models\Branch;
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
        $branches = Branch::all();
        $products = Product::all();

        // Create stock for each product in each branch
        foreach ($branches as $branch) {
            foreach ($products as $product) {
                Stock::create([
                    'product_id' => $product->id,
                    'branch_id' => $branch->id,
                    'quantity' => fake()->numberBetween(50, 200),
                ]);
            }
        }
    }
}
