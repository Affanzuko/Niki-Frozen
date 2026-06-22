<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaction_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained('transactions')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('product_name', 150); // snapshot nama produk saat transaksi
            $table->decimal('unit_price', 10, 2); // snapshot harga saat transaksi
            $table->decimal('discounted_price', 10, 2)->nullable();
            $table->integer('quantity');
            $table->decimal('subtotal', 12, 2); // = (discounted_price ?? unit_price) * quantity
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_items');
    }
};
