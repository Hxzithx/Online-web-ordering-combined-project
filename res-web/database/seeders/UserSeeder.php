<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create a Super Admin / Manager
        $admin = User::firstOrCreate(
            ['email' => 'admin@resto.com'],
            [
                'first_name' => 'Hasitha',
                'last_name' => 'Lakruwan',
                'phone' => '0760700111',
                'address' => 'Main Street, Matale',
                'role' => 'admin',
                'status' => 'active',
                'password' => Hash::make('password'),
                'branch_id' => 1,
                'user_id' => null, // Top level user
            ]
        );

        // 2. Create a Steward
        User::firstOrCreate(
            ['email' => 'kamal@resto.com'],
            [
                'first_name' => 'Kamal',
                'last_name' => 'Perera',
                'phone' => '0771234567',
                'address' => 'Kandy Road, Matale',
                'role' => 'steward',
                'status' => 'active',
                'password' => Hash::make('password'),
                'branch_id' => 1,
                'user_id' => $admin->id, // Managed by Admin
            ]
        );

        // 3. Create a Cashier
        User::firstOrCreate(
            ['email' => 'priyani@resto.com'],
            [
                'first_name' => 'Priyani',
                'last_name' => 'Fernando',
                'phone' => '0719876543',
                'address' => 'Colombo St, Matale',
                'role' => 'cashier',
                'status' => 'active',
                'password' => Hash::make('password'),
                'branch_id' => 1,
                'user_id' => $admin->id,
            ]
        );

        // 4. Create a regular Customer/User
        User::firstOrCreate(
            ['email' => 'customer@example.com'],
            [
                'first_name' => 'Arjun',
                'last_name' => 'Reddy',
                'phone' => '0755554444',
                'address' => 'No 45, Nuwara Eliya',
                'role' => 'user',
                'status' => 'active',
                'password' => Hash::make('password'),
                'branch_id' => 1,
                'user_id' => null,
            ]
        );

        $this->command->info('Users seeded successfully!');
    }
}