<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $adminId = User::where('role', 'admin')->first()?->id ?? 1;

        $categories = [
            ['id' => 1, 'name' => 'Main Course', 'description' => 'Heavy meals and rice dishes', 'user_id' => $adminId],
            ['id' => 2, 'name' => 'Beverages', 'description' => 'Hot and cold drinks', 'user_id' => $adminId],
            ['id' => 3, 'name' => 'Desserts', 'description' => 'Sweet treats', 'user_id' => $adminId],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->updateOrInsert(['id' => $category['id']], array_merge($category, [
                'created_at' => now(),
                'updated_at' => now()
            ]));
        }
    }
}