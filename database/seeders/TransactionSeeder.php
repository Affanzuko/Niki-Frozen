<?php

namespace Database\Seeders;

use App\Models\CashierSession;
use App\Models\Transaction;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cashierSessions = CashierSession::all();

        // Buat transaksi untuk 30 hari terakhir agar chart lebih hidup
        foreach ($cashierSessions as $session) {
            for ($i = 0; $i <= 30; $i++) {
                $targetDate = now()->subDays($i);
                
                // Random jumlah transaksi per hari (2 - 8 transaksi per sesi)
                $txCount = rand(2, 8);
                
                for ($j = 0; $j < $txCount; $j++) {
                    $randomTime = $targetDate->copy()->addHours(rand(8, 21))->addMinutes(rand(0, 59));
                    
                    Transaction::factory()->create([
                        'cashier_session_id' => $session->id,
                        'branch_id' => $session->branch_id,
                        'transaction_code' => 'TRX-' . $randomTime->format('dmY') . '-' . strtoupper(substr(uniqid(), -5)),
                        'created_at' => $randomTime,
                        'updated_at' => $randomTime,
                    ]);
                }
            }
        }
    }
}
