<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExtraService;

class ExtraServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'serviceName' => 'Premium Support',
                'description' => '24/7 dedicated support for clients',
                'default_price' => 1500.00,
                'cost_price' => 1000.00,
                'is_tax_included' => true,
                'is_service_charge_included' => false,
                'applicable_order_types_id' => 'general',
                'is_active' => true,
                'measure_unit' => 'per month',
                'is_synced' => false,
                'sync_date_time' => null,
            ],
            [
                'serviceName' => 'On-site Installation',
                'description' => 'Technician visit for installation',
                'default_price' => 5000.00,
                'cost_price' => 3500.00,
                'is_tax_included' => true,
                'is_service_charge_included' => true,
                'applicable_order_types_id' => 'installation',
                'is_active' => true,
                'measure_unit' => 'per job',
                'is_synced' => false,
                'sync_date_time' => null,
            ],
            [
                'serviceName' => 'Extended Warranty',
                'description' => 'Additional 2 years warranty coverage',
                'default_price' => 2000.00,
                'cost_price' => 1200.00,
                'is_tax_included' => false,
                'is_service_charge_included' => false,
                'applicable_order_types_id' => 'warranty',
                'is_active' => true,
                'measure_unit' => 'per product',
                'is_synced' => false,
                'sync_date_time' => null,
            ],
        ];

        foreach ($services as $service) {
            ExtraService::create($service);
        }
    }
}


