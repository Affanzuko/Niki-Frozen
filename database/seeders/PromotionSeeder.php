<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Product;
use App\Models\Promotion;
use App\Models\User;
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
        $admins = User::where('role', 'admin')->get();

        // Create promotions for random products per branch
        foreach ($branches as $branch) {
            $branchProducts = Product::where('branch_id', $branch->id)->get();
            $branchAdmin = $admins->where('branch_id', $branch->id)->first()
                           ?? $admins->first();

            if ($branchProducts->isEmpty() || !$branchAdmin) {
                continue;
            }

            // Pick 5 random products from this branch for promotions
            $promoProducts = $branchProducts->random(min(5, $branchProducts->count()));

            foreach ($promoProducts as $product) {
                $batch = \App\Models\StockIncoming::where('product_id', $product->id)
                    ->where('branch_id', $branch->id)
                    ->first();

                if (!$batch) {
                    continue;
                }

                Promotion::factory()
                    ->create([
                        'product_id' => $product->id,
                        'stock_incoming_id' => $batch->id,
                        'branch_id' => $branch->id,
                        'created_by' => $branchAdmin->id,
                        'quota' => rand(5, 20),
                    ]);
            }
        }
    }
}
