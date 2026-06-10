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
        $admins = User::where('role', 'admin')->get();

        // Create stock incoming records per branch
        foreach ($branches as $branch) {
            $branchProducts = Product::where('branch_id', $branch->id)->get();
            $branchAdmin = $admins->where('branch_id', $branch->id)->first()
                           ?? $admins->first();

            if ($branchProducts->isEmpty() || !$branchAdmin) {
                continue;
            }

            // Create incoming stock for 5 random products per branch
            $selectedProducts = $branchProducts->random(min(5, $branchProducts->count()));

            foreach ($selectedProducts as $product) {
                StockIncoming::factory()
                    ->create([
                        'product_id' => $product->id,
                        'branch_id' => $branch->id,
                        'user_id' => $branchAdmin->id,
                    ]);
            }
        }
    }
}
