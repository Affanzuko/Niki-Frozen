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
        $transactions = Transaction::all();
        $products = Product::all();

        // Create transaction items for each transaction
        foreach ($transactions as $transaction) {
            // Each transaction has 2-5 items
            $itemCount = fake()->numberBetween(2, 5);

            for ($i = 0; $i < $itemCount; $i++) {
                $product = $products->random();

                TransactionItem::factory()
                    ->create([
                        'transaction_id' => $transaction->id,
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'unit_price' => $product->price,
                    ]);
            }
        }
    }
}
