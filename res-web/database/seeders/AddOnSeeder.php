<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AddOn;

class AddOnSeeder extends Seeder
{
    public function run(): void
    {
        $addOns = [
            [
                'name' => 'Extra Cheese',
                'cost_price' => 50.00,
                'price' => 100.00,
                'packaging_charge' => 0.00,
                'include_tax' => true,
                'include_service_charge' => true,
                'status' => 'active',
            ],
            [
                'name' => 'Add Bacon',
                'cost_price' => 100.00,
                'price' => 250.00,
                'packaging_charge' => 10.00,
                'include_tax' => true,
                'include_service_charge' => true,
                'status' => 'active',
            ],
            [
                'name' => 'Spicy Sauce',
                'cost_price' => 20.00,
                'price' => 50.00,
                'packaging_charge' => 0.00,
                'include_tax' => false,
                'include_service_charge' => false,
                'status' => 'active',
            ],
        ];

        foreach ($addOns as $addOn) {
            AddOn::create($addOn);
        }
    }
}