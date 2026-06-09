<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\CashierSession;
use App\Models\User;
use Illuminate\Database\Seeder;

class CashierSessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $branches = Branch::all();
        $cashiers = User::where('role', 'cashier')->get();

        // Create cashier sessions for each branch
        foreach ($branches as $branch) {
            // Get cashiers for this branch
            $branchCashiers = $cashiers->where('branch_id', $branch->id);

            if ($branchCashiers->count() > 0) {
                foreach ($branchCashiers as $cashier) {
                    // Create multiple sessions for each cashier
                    CashierSession::factory(3)
                        ->create([
                            'user_id' => $cashier->id,
                            'branch_id' => $branch->id,
                        ]);
                }
            }
        }
    }
}
