<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KitchenSeeder extends Seeder
{
    public function run(): void
    {
        $adminId = User::where('role', 'admin')->first()?->id ?? 1;

        $kitchens = [
            [
                'id' => 1,
                'name' => 'Main Kitchen',
                'location' => 'Ground Floor',
                'status' => 'active',
                'user_id' => $adminId,
            ],
            [
                'id' => 2,
                'name' => 'Beverage Bar',
                'location' => 'Main Hall',
                'status' => 'active',
                'user_id' => $adminId,
            ]
        ];

        foreach ($kitchens as $kitchen) {
            DB::table('kitchens')->updateOrInsert(['id' => $kitchen['id']], array_merge($kitchen, [
                'created_at' => now(),
                'updated_at' => now()
            ]));
        }
    }
}