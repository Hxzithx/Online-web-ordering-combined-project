<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PortionSeeder extends Seeder
{
    public function run(): void
    {
        $portions = [
            [
                'id' => 1,
                'name' => 'Full',
                'description' => 'Standard full portion size',
                'multiplier' => 1.00,
                'additional_price' => 100.00,
            ],
            [
                'id' => 2,
                'name' => 'Half',
                'description' => 'Half size portion',
                'multiplier' => 0.60, // Typically priced slightly higher than 50%
                'additional_price' => 50.00,
            ],
            [
                'id' => 3,
                'name' => 'Regular',
                'description' => 'Default portion for snacks/drinks',
                'multiplier' => 1.00,
                'additional_price' => 0.00,
            ],
        ];

        foreach ($portions as $portion) {
            DB::table('portions')->updateOrInsert(
                ['id' => $portion['id']],
                array_merge($portion, [
                    'created_at' => now(),
                    'updated_at' => now()
                ])
            );
        }

        $this->command->info('Portions seeded successfully!');
    }
}