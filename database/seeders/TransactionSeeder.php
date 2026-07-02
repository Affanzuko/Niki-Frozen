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

        // Buat transaksi sesuai dengan hari sesi kasir beroperasi
        foreach ($cashierSessions as $session) {
            $targetDate = \Carbon\Carbon::parse($session->date);
            
            // Random jumlah transaksi per sesi (misal: 5 - 15 transaksi)
            $txCount = rand(5, 15);
            
            for ($j = 0; $j < $txCount; $j++) {
                if ($session->shift === 'pagi') {
                    // Pagi: 07:00 - 13:59
                    $randomTime = $targetDate->copy()->addHours(rand(7, 13))->addMinutes(rand(0, 59));
                } else {
                    // Siang: 14:00 - 20:59
                    $randomTime = $targetDate->copy()->addHours(rand(14, 20))->addMinutes(rand(0, 59));
                }
                
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
