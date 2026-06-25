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
            'username' => 'owner1',
            'password' => Hash::make('owner1'),
            'role' => 'owner',
        ]);

        // Create general manager for each branch

        User::create([
            'branch_id' => null,
            'name' => 'General Manager NikiFrozen',
            'username' => 'gm',
            'password' => Hash::make('gm'),
            'role' => 'general_manager',
        ]);


        // Create admin for each branch (1 admin per branch)
        foreach ($branches as $branch) {
            User::create([
                'branch_id' => $branch->id,
                'name' => 'Admin ' . $branch->name,
                'username' => 'admin' . $branch->id,
                'password' => Hash::make('admin' . $branch->id),
                'role' => 'admin',
            ]);
        }

        // Create cashier users for each branch (2 per branch)
        foreach ($branches as $branch) {
            User::create([
                'branch_id' => $branch->id,
                'name' => 'kasir ' . $branch->name,
                'username' => 'kasir' . $branch->id,
                'password' => Hash::make('kasir' . $branch->id),
                'role' => 'cashier',
            ]);
        }
    }
}
