<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Product;
use App\Models\StockIncoming;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StockIncomingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $branches = Branch::all();
        $products = Product::all();
        $users = User::where('role', 'admin')->get();

        // Create stock incoming records
        foreach ($branches as $branch) {
            foreach ($products->random(min(5, $products->count())) as $product) {
                StockIncoming::factory()
                    ->create([
                        'product_id' => $product->id,
                        'branch_id' => $branch->id,
                        'user_id' => $users->random()->id ?? $users->first()->id,
                    ]);
            }
        }
    }
}
