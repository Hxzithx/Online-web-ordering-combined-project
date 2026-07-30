<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ItemPrice;
use App\Models\Item;
use App\Models\OrderType;

class ItemPriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some existing IDs to link the prices to
        $item = Item::first();
        $dineIn = OrderType::where('name', 'Dine-in')->first();
        $takeaway = OrderType::where('name', 'Takeaway')->first();

        // Check if we have the necessary data before seeding
        if ($item && $dineIn) {
            ItemPrice::create([
                'item_id'           => 1,
                'variant_id'        => null, // Optional field
                'portion_id'        => null, // Optional field
                'order_type_id'     => $dineIn->id,
                'cost_price'        => 450.00,
                'price'             => 800.00,
                'package_charge'    => 0.00,
                'have_deposit_item' => false,
            ]);
        }

        if ($item && $takeaway) {
            ItemPrice::create([
                'item_id'           => 2,
                'variant_id'        => null,
                'portion_id'        => null,
                'order_type_id'     => $takeaway->id,
                'cost_price'        => 500.00,
                'price'             => 900.00, // Higher price for takeaway
                'package_charge'    => 20.00,  // Added package charge
                'have_deposit_item' => false,
            ]);
        }
        if ($item && $takeaway) {
            ItemPrice::create([
                'item_id'           => 3,
                'variant_id'        => null,
                'portion_id'        => null,
                'order_type_id'     => $takeaway->id,
                'cost_price'        => 550.00,
                'price'             => 1000.00, // Higher price for takeaway
                'package_charge'    => 20.00,  // Added package charge
                'have_deposit_item' => false,
            ]);
        }
        if ($item && $dineIn) {
            ItemPrice::create([
                'item_id'           => 4,
                'variant_id'        => null, // Optional field
                'portion_id'        => null, // Optional field
                'order_type_id'     => $dineIn->id,
                'cost_price'        => 450.00,
                'price'             => 800.00,
                'package_charge'    => 0.00,
                'have_deposit_item' => false,
            ]);
        }
    }
}

