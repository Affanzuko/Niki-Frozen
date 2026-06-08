<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $topProducts = [
            ['barcode' => '8991234567801', 'name' => 'Fiesta Chicken Nugget 500g', 'price' => 45500],
            ['barcode' => '8991234567802', 'name' => 'Kanzler Sosis Bakar Keju 500g', 'price' => 52000],
            ['barcode' => '8991234567803', 'name' => 'Golden Farm French Fries 1kg', 'price' => 38000],
            ['barcode' => '8991234567804', 'name' => 'Cedea Dumpling Keju 500g', 'price' => 32500],
        ];

        foreach ($topProducts as $item) {
            Product::create($item);
        }

        Product::factory(46)->create();
    }
}
