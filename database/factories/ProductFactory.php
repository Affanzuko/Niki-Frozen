<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $brands = ['Fiesta', 'Champ', 'Belfoods', 'Cedea', 'Kanzler', 'So Good', 'Bernardi', 'Golden Farm', 'Minaku', 'Vigo'];
        $types = [
            'Chicken Nugget', 'Sosis Sapi Bakar', 'Sosis Ayam', 'Bakso Sapi',
            'Bakso Ikan', 'French Fries Shoestring', 'French Fries Crinkle',
            'Spicy Chicken Wings', 'Chicken Karage', 'Dumpling Keju',
            'Fish Roll', 'Otak-otak Singapore', 'Steamboat Set', 'Daging Slice'
        ];
        $weights = ['250g', '500g', '900g', '1kg'];
        $productName = $this->faker->randomElement($brands) . ' ' .
                       $this->faker->randomElement($types) . ' ' .
                       $this->faker->randomElement($weights);

        $price = floor($this->faker->numberBetween(15000, 95000) / 500) * 500;

        return [
            'branch_id' => null,              // Akan diset saat relasi
            'product_category_id' => null,     // Akan diset saat relasi
            'product_unit_id' => null,         // Akan diset saat relasi
            'barcode' => $this->faker->unique()->ean13(),
            'name'    => $productName,
            'price'   => $price,
        ];
    }
}
