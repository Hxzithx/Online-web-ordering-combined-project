<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
        //UserSeeder::class,          // 1. Mandatory for user_id
        KitchenSeeder::class,       // 2. Mandatory for item kitchen_id
        CategorySeeder::class,      // 3. Mandatory for item category_id
        VariantSeeder::class,       // 4. Mandatory for item variant_id
        PortionSeeder::class,       // 5. NEW: Mandatory for item portions_id
        OrderTypeSeeder::class,     // 6. Mandatory for orders/items
        PaymentMethodSeeder::class, // 7. Mandatory for orders
        TableSeeder::class,         // 8. Mandatory for orders
        TableMergeSeeder::class,
        ItemSeeder::class,          // 9. Mandatory for order items
        ItemPriceSeeder::class,
        ExtraServiceSeeder::class,
        PackageSeeder::class,
        PackageItemSeeder::class,
        AddOnSeeder::class,
        OrderSeeder::class,         // 10. Mandatory for order items
        OrderItemSeeder::class,     // 11. Final child records
        OrderItemAddOnSeeder::class,
        // CustomerSeeder::class,
        ]);
    }
}


