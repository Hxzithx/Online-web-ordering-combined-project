<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packages = [
            [
                'name'           => 'Family Feast Bundle',
                'code'           => 'PKG-FAM-01',
                'description'    => 'Includes 2 Large Pizzas, 1 Family Pasta, and 4 Drinks.',
                'cost_price'     => 4500,
                'actual_price'   => 6500,
                'discounted_price' => 500,
                'price'          => 6000,
                'status'         => 'active',
            ],
            [
                'name'           => 'Couple Combo',
                'code'           => 'PKG-CPL-02',
                'description'    => 'Perfect for two: 2 Medium Mains and 2 Desserts.',
                'cost_price'     => 2500,
                'actual_price'   => 3500,
                'discounted_price' => 300,
                'price'          => 3200,
                'status'         => 'active',
            ],
            [
                'name'           => 'Office Lunch Pack',
                'code'           => 'PKG-OFF-03',
                'description'    => '10 Individual Lunch Boxes with Mixed Selections.',
                'cost_price'     => 12000,
                'actual_price'   => 18000,
                'discounted_price' => 2000,
                'price'          => 16000,
                'status'         => 'inactive',
            ],
        ];

        foreach ($packages as $packageData) {
            Package::create($packageData);
        }
    }
}