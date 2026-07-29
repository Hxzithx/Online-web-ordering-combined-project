<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VariantSeeder extends Seeder
{
    public function run(): void
    {
        $variants = [
            [
                'id' => 1,
                'name' => 'Regular',
                'description' => 'Standard portion/style',
                'multiplier' => 1.00,
                'additional_price' => 0.00,
            ],
            [
                'id' => 2,
                'name' => 'Large',
                'description' => 'Upsized portion',
                'multiplier' => 1.50,
                'additional_price' => 200.00,
            ],
            [
                'id' => 3,
                'name' => 'Extra Spicy',
                'description' => 'Added spice level',
                'multiplier' => 1.00,
                'additional_price' => 50.00,
            ],
        ];

        foreach ($variants as $variant) {
            DB::table('variants')->updateOrInsert(
                ['id' => $variant['id']],
                array_merge($variant, [
                    'created_at' => now(),
                    'updated_at' => now()
                ])
            );
        }

        $this->command->info('Item variants seeded successfully!');
    }
}