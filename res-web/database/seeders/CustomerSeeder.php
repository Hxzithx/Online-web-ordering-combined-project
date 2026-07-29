<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data to avoid unique email errors
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Customer::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $customers = [
            [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john@example.com',
                'phone' => '0771234567',
                'address' => '123 Main St',
                'city' => 'Colombo',
                'state' => 'Western',
                'postal_code' => '00100',
                'country' => 'Sri Lanka',
                'password'   => Hash::make('123456'),
            ],
            [
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'email' => 'jane@example.com',
                'phone' => '0719876543',
                'address' => '456 Galle Road',
                'city' => 'Kandy',
                'state' => 'Central',
                'postal_code' => '20000',
                'country' => 'Sri Lanka',
                'password'   => Hash::make('123456'),
            ],
            [
                'first_name' => 'Jayalal', // Sample based on your profile
                'last_name' => 'Pathmasiri',
                'email' => 'jayalal_test@gmail.com',
                'phone' => '0765432311',
                'address' => 'Walapane',
                'city' => 'Nuwara Eliya',
                'state' => 'Central',
                'postal_code' => '22200',
                'country' => 'Sri Lanka',
                'password'   => Hash::make('123456'),
            ],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }
    }
}