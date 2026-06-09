<?php

namespace Database\Seeders;

use App\Models\ExpiryNotification;
use App\Models\StockIncoming;
use Illuminate\Database\Seeder;

class ExpiryNotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stockIncomings = StockIncoming::all();

        // Create expiry notifications for stock incomings
        foreach ($stockIncomings->random(min(20, $stockIncomings->count())) as $stockIncoming) {
            ExpiryNotification::factory()
                ->create([
                    'stock_incoming_id' => $stockIncoming->id,
                    'branch_id' => $stockIncoming->branch_id,
                ]);
        }
    }
}
