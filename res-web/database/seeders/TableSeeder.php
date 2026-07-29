<?php

namespace Database\Seeders;

use App\Models\Table;
use App\Models\User;
use Illuminate\Database\Seeder;

class TableSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Get a user ID to associate with the tables (required by your migration)
        $user = User::first() ?? User::factory()->create(['role' => 'admin']);

        $restaurantTables = [
            [
                'table_number' => 'T1',
                'capacity' => 2,
                'zone' => 'Main Hall',
                'status' => 'available',
                'location' => 'Indoor',
                'table_type' => 'standard',
                'user_id' => $user->id,
            ],
            [
                'table_number' => 'T2',
                'capacity' => 4,
                'zone' => 'Main Hall',
                'status' => 'available',
                'location' => 'Indoor',
                'table_type' => 'standard',
                'user_id' => $user->id,
            ],
            [
                'table_number' => 'V1',
                'capacity' => 8,
                'zone' => 'VIP Lounge',
                'status' => 'available',
                'location' => 'Indoor',
                'table_type' => 'vip',
                'user_id' => $user->id,
            ],
            [
                'table_number' => 'O1',
                'capacity' => 4,
                'zone' => 'Garden',
                'status' => 'available',
                'location' => 'Indooor',
                'table_type' => 'standard',
                'user_id' => $user->id,
            ],
            [
                'table_number' => 'T3',
                'capacity' => 5,
                'zone' => 'Main Hall',
                'status' => 'available',
                'location' => 'Outdoor',
                'table_type' => 'standard',
                'user_id' => $user->id,
            ],
        ];

        foreach ($restaurantTables as $tableData) {
            Table::create($tableData);
        }

        $this->command->info('Tables seeded successfully with zones and user IDs!');
    }
}
