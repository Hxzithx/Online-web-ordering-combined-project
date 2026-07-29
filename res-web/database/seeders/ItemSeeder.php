<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        $adminId = User::where('role', 'admin')->first()?->id ?? 1;

        $items = [
            [
                'id' => 1, // Matches your Kottu entry in OrderItems
                'category_id' => 1,
                'variant_id' => 1,
                'portions_id' => 1,
                'kitchen_id' => 1,
                'user_id' => $adminId,
                'item_type' => 'non-veg',
                'base_name' => 'Chicken Kottu',
                'status' => 'active',
                'include_tax' => true,
                'include_service_charge' => true,
            ],
            [
                'id' => 4, // Matches your Iced Coffee entry in OrderItems
                'category_id' => 2,
                'variant_id' => 1,
                'portions_id' => 3,
                'kitchen_id' => 2,
                'user_id' => $adminId,
                'item_type' => 'drink',
                'base_name' => 'Ice Coffee',
                'status' => 'active',
                'include_tax' => false,
                'include_service_charge' => false,
            ],
            [
                'id' => 2, // Matches your Iced Coffee entry in OrderItems
                'category_id' => 1,
                'variant_id' => 1,
                'portions_id' => 3,
                'kitchen_id' => 2,
                'user_id' => $adminId,
                'item_type' => 'non-veg',
                'base_name' => 'Grilled Chiken',
                'status' => 'active',
                'include_tax' => false,
                'include_service_charge' => true,
            ],
            [
                'id' => 3, // Matches your Iced Coffee entry in OrderItems
                'category_id' => 1,
                'variant_id' => 1,
                'portions_id' => 3,
                'kitchen_id' => 2,
                'user_id' => $adminId,
                'item_type' => 'non-veg',
                'base_name' => 'Chiken Salad',
                'description' => 'Vegitable with chiken and mayonnaise',
                'status' => 'active',
                'include_tax' => true,
                'include_service_charge' => true,
            ]
        ];

        foreach ($items as $item) {
            DB::table('items')->updateOrInsert(['id' => $item['id']], array_merge($item, [
                'created_at' => now(),
                'updated_at' => now()
            ]));
        }
    }
}