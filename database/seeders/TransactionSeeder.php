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

        // Create transactions for each cashier session
        foreach ($cashierSessions as $session) {
            Transaction::factory(50)
                ->create([
                    'cashier_session_id' => $session->id,
                    'branch_id' => $session->branch_id,
                ]);
        }
    }
}
