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
                // Re-index to ensure alternating shifts
                $branchCashiers = $branchCashiers->values();
                
                foreach ($branchCashiers as $index => $cashier) {
                    // Alternate shifts between cashiers
                    $shift = ($index % 2 == 0) ? 'pagi' : 'siang';

                    // Create sessions for the past 3 days + today
                    for ($i = 0; $i < 90; $i++) {
                        $date = today()->subDays($i);
                        CashierSession::factory()->create([
                            'user_id' => $cashier->id,
                            'branch_id' => $branch->id,
                            'shift' => $shift,
                            'date' => $date->format('Y-m-d'),
                        ]);
                    }
                }
            }
        }
    }
}
