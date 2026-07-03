<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            BranchSeeder::class,
            ProductCategorySeeder::class,
            ProductUnitSeeder::class,
            UserSeeder::class,
            ProductSeeder::class,
            StockIncomingSeeder::class,
            StockSeeder::class,
            CashierSessionSeeder::class,
            TransactionSeeder::class,
            TransactionItemSeeder::class,
            // ExpiryNotificationSeeder::class, // Dimatikan agar tidak membuat notifikasi palsu
            PromotionSeeder::class,
        ]);
    }
}
