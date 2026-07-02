<?php

namespace Database\Seeders;

use App\Models\Stock;
use App\Models\StockIncoming;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Stok dihitung berdasarkan data dari tabel stock_incomings.
     * Quantity pada tabel stocks = SUM(quantity) dari stock_incomings
     * yang memiliki product_id dan branch_id yang sama.
     */
    public function run(): void
    {
        // Aggregate total quantity per product per branch dari stock_incomings
        $aggregated = StockIncoming::query()
            ->select('product_id', 'branch_id', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('product_id', 'branch_id')
            ->get();

        foreach ($aggregated as $row) {
            Stock::factory()->create([
                'product_id' => $row->product_id,
                'branch_id'  => $row->branch_id,
                'quantity'   => $row->total_quantity,
            ]);
        }
    }
}
