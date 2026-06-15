<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Database\Seeder;

class TransactionItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $transactions = Transaction::doesntHave('items')->get();

        // Create transaction items for each transaction
        foreach ($transactions as $transaction) {
            // Get products from the same branch as the transaction
            $branchProducts = Product::where('branch_id', $transaction->branch_id)->get();

            if ($branchProducts->isEmpty()) {
                continue;
            }

            // Each transaction has 2-4 items
            $itemCount = fake()->numberBetween(2, 4);
            $selectedProducts = $branchProducts->random(min($itemCount, $branchProducts->count()));

            $totalPrice = 0;

            foreach ($selectedProducts as $product) {
                $quantity = fake()->numberBetween(1, 5);
                $unitPrice = $product->price;
                $discountPercentage = fake()->randomElement([0, 0, 0, 10, 15, 20]); // 50% chance no discount
                $discountedPrice = $discountPercentage > 0
                    ? round($unitPrice * (100 - $discountPercentage) / 100, 2)
                    : null;
                $subtotal = ($discountedPrice ?? $unitPrice) * $quantity;

                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'unit_price' => $unitPrice,
                    'discounted_price' => $discountedPrice,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal,
                ]);

                $totalPrice += $subtotal;
            }

            // Update total_price on transaction to match actual items
            $transaction->update(['total_price' => $totalPrice]);
        }
    }
}
