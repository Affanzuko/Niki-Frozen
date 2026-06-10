<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Branch;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $branches = Branch::all();

        // Create owner user (no specific branch — oversees all)
        User::create([
            'branch_id' => null,
            'name' => 'Pemilik NikiFrozen',
            'username' => 'owner',
            'password' => Hash::make('password'),
            'role' => 'owner',
        ]);

        // Create general manager for each branch
        foreach ($branches as $branch) {
            User::factory()
                ->manager()
                ->create([
                    'branch_id' => $branch->id,
                    'name' => 'Manager ' . $branch->name,
                ]);
        }

        // Create admin for each branch (1 admin per branch)
        foreach ($branches as $branch) {
            User::factory()
                ->admin()
                ->create([
                    'branch_id' => $branch->id,
                    'name' => 'Admin ' . $branch->name,
                ]);
        }

        // Create cashier users for each branch (2 per branch)
        foreach ($branches as $branch) {
            User::factory(2)->cashier()->create([
                'branch_id' => $branch->id,
            ]);
        }
    }
}
