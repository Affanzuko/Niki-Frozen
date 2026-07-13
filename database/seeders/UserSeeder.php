<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Branch;
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
            'name' => 'Owner',
            'username' => 'owner1',
            'password' => Hash::make('fufufafa2014'),
            'role' => 'owner',
        ]);

        // Create general manager for each branch

        User::create([
            'branch_id' => null,
            'name' => 'General Manager',
            'username' => 'gm1',
            'password' => Hash::make('fufufafa2014'),
            'role' => 'general_manager',
        ]);

        foreach ($branches as $branch) {
            $branchName = str_replace('Niki Frozen - ', '', $branch->name);
            User::create([
                'branch_id' => $branch->id,
                'name' => $branchName,
                'username' => 'admin' . $branch->id,
                'password' => Hash::make('fufufafa2014'),
                'role' => 'admin',
            ]);
        }

        foreach ($branches as $branch) {
            $branchName = str_replace('Niki Frozen - ', '', $branch->name);
            User::create([
                'branch_id' => $branch->id,
                'name' => $branchName,
                'username' => 'kasir' . $branch->id,
                'password' => Hash::make('fufufafa2014'),
                'role' => 'cashier',
            ]);
        }
    }
}
