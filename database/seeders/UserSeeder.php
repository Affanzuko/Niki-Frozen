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

        // Create owner user
        User::create([
            'branch_id' => $branches->first()->id,
            'name' => 'Pemilik NikiFrozen',
            'username' => 'owner',
            'password' => Hash::make('password'),
            'role' => 'owner',
        ]);

        // Create general managers
        foreach ($branches as $branch) {
            User::factory()
                ->manager()
                ->create([
                    'branch_id' => $branch->id,
                    'name' => 'Manager ' . $branch->name,
                ]);
        }

        // Create admin users
        User::factory(3)->admin()->create([
            'branch_id' => $branches->random()->id,
        ]);

        // Create cashier users for each branch
        foreach ($branches as $branch) {
            User::factory(3)->cashier()->create([
                'branch_id' => $branch->id,
                'name' => fake()->firstName() . ' (Cashier)',
            ]);
        }
    }
}
