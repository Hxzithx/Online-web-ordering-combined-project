<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'name' => 'Dine-in',
                'description' => 'Customer eating inside the restaurant',
                'requires_table' => true,
                'allow_service_charge' => true,
                'allow_packaging_charge' => false,
                'allow_split_payment' => true,
                'allow_discounts' => true,
                'tax_inclusive' => false,
                'steward_required' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Takeaway',
                'description' => 'Customer picks up food at the counter',
                'requires_table' => false,
                'allow_service_charge' => false,
                'allow_packaging_charge' => true,
                'allow_split_payment' => false,
                'allow_discounts' => true,
                'tax_inclusive' => true,
                'steward_required' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Delivery',
                'description' => 'Home delivery via restaurant riders or apps',
                'requires_table' => false,
                'allow_service_charge' => false,
                'allow_packaging_charge' => true,
                'allow_split_payment' => false,
                'allow_discounts' => true,
                'tax_inclusive' => true,
                'steward_required' => false,
                'is_active' => true,
            ]
        ];

        foreach ($types as $type) {
            // Using updateOrInsert to prevent duplicate errors if you run it twice
            DB::table('order_types')->updateOrInsert(
                ['name' => $type['name']], 
                array_merge($type, ['created_at' => now(), 'updated_at' => now()])
            );
        }
    }
}