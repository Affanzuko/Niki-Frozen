<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Product;
use App\Models\Promotion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PromotionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $branches = Branch::all();
        $products = Product::all();
        $admins = User::where('role', 'admin')->get();

        // Create promotions for random products
        foreach ($branches as $branch) {
            foreach ($products->random(min(10, $products->count())) as $product) {
                Promotion::factory()
                    ->create([
                        'product_id' => $product->id,
                        'branch_id' => $branch->id,
                        'created_by' => $admins->random()->id ?? $admins->first()->id,
                    ]);
            }
        }
    }
}
