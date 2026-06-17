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
        Schema::table('stock_incomings', function (Blueprint $table) {
            $table->integer('current_quantity')->default(0)->after('quantity');
        });

        // Initialize current_quantity with quantity for existing records
        DB::statement('UPDATE stock_incomings SET current_quantity = quantity');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_incomings', function (Blueprint $table) {
            $table->dropColumn('current_quantity');
        });
    }
};
