<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        $methods = [
            [
                'name' => 'Cash',
                'code' => 'CASH',
                'type' => 'physical',
                'is_online' => false,
                'requires_reference' => false,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Credit/Debit Card',
                'code' => 'CARD',
                'type' => 'electronic',
                'is_online' => true,
                'requires_reference' => true, // To store last 4 digits or ref number
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'LankaQR / Mobile Pay',
                'code' => 'QR',
                'type' => 'electronic',
                'is_online' => true,
                'requires_reference' => true,
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($methods as $method) {
            DB::table('payment_methods')->updateOrInsert(
                ['code' => $method['code']],
                array_merge($method, [
                    'created_at' => now(),
                    'updated_at' => now()
                ])
            );
        }

        $this->command->info('Payment methods seeded!');
    }
}