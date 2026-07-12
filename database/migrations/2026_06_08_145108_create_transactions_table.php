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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cashier_session_id')->constrained('cashier_sessions')->onDelete('cascade');
            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade');
            $table->string('transaction_code', 50)->unique(); // Digunakan sebagai order_id di Midtrans
            $table->enum('payment_method', ['cash', 'qris']);
            $table->decimal('total_price', 12, 2);
            $table->enum('status', ['pending', 'success', 'failed', 'return'])->default('pending');

            // Integrasi midtrans
            $table->string('snap_token')->nullable();
            $table->string('midtrans_transaction_id')->nullable(); // ID unik dari server Midtrans

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
