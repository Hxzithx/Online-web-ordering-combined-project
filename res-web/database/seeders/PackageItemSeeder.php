<?php

namespace Database\Seeders;

use App\Models\PackageItem;
use Illuminate\Database\Seeder;

class PackageItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $records = [
            [
                'package_id' => 1,
                'item_id' => 1,
                'extra_service_id' => 1,
                'quantity' => 2,
                'cost_price' => 1000.00,
                'price' => 1500.00,
                'status' => 'active',
                'is_synced' => false,
                'sync_date_time' => null,
            ],
            [
                'package_id' => 1,
                'item_id' => 1,
                'extra_service_id' => 2,
                'quantity' => 1,
                'cost_price' => 500.00,
                'price' => 800.00,
                'status' => 'active',
                'is_synced' => false,
                'sync_date_time' => null,
            ],
            [
                'package_id' => 2,
                'item_id' => 4,
                'extra_service_id' => 3,
                'quantity' => 3,
                'cost_price' => 1200.00,
                'price' => 1800.00,
                'status' => 'inactive',
                'is_synced' => false,
                'sync_date_time' => null,
            ],
        ];

        foreach ($records as $record) {
            PackageItem::create($record);
        }
    }
}
