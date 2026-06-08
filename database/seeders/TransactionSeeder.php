<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\TransactionItem;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        // Transaction::factory(50)->create()->each(function ($transaction) {
        //     $items = TransactionItem::factory(rand(1, 4))->make([
        //         'transaction_id' => $transaction->id
        //     ]);
        //     $transaction->items()->saveMany($items);
        //     $totalPrice = $items->sum('subtotal');
        //     $transaction->update([
        //         'total_price' => $totalPrice
        //     ]);
        // });
    }
}
