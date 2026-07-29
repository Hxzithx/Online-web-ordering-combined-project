<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class OrderItemSeeder extends Seeder
{
    public function run(): void
    {
        // Get the first order we created in OrderSeeder
        $order = Order::first();

        if (!$order) {
            $this->command->error('No orders found. Run OrderSeeder first.');
            return;
        }

        $items = [
            [
                'order_id' => $order->id,
                'order_type_id' => 1, // Dine-in
                'item_id' => 1, // Chicken Kottu
                'quantity' => 2,
                'unit_price' => 1200.00,
                'subtotal' => 2400.00,
                'tax_percentage' => 10,
                'tax_amount' => 240.00,
                'service_charge_percentage' => 5,
                'service_charge_amount' => 120.00,
                'discount_amount' => 0,
                'status' => 'completed',
                'kitchen_id' => 1,
                'is_voided' => 0,
                'void_reason' => 'leave',
                'added_at' => Carbon::now()->subMinutes(50),
                'accepted_at' => Carbon::now()->subMinutes(48),
                'started_at' => Carbon::now()->subMinutes(45),
                'completed_at' => Carbon::now()->subMinutes(30),
            ],
            [
                'order_id' => $order->id,
                'order_type_id' => 1,
                'item_id' => 4, // Iced Coffee
                'quantity' => 1,
                'unit_price' => 450.00,
                'subtotal' => 450.00,
                'tax_percentage' => 10,
                'tax_amount' => 45.00,
                'service_charge_percentage' => 5,
                'service_charge_amount' => 22.50,
                'discount_amount' => 0,
                'status' => 'completed',
                'kitchen_id' => 2, // Beverage Bar
                'is_voided' => 0,
                'void_reason' => 'customer leave',
                'added_at' => Carbon::now()->subMinutes(50),
                'accepted_at' => Carbon::now()->subMinutes(49),
                'started_at' => Carbon::now()->subMinutes(48),
                'completed_at' => Carbon::now()->subMinutes(45),
            ]
        ];

        foreach ($items as $itemData) {
            // Using OrderItem model directly to bypass factories
            \App\Models\OrderItem::create($itemData);
        }

        $this->command->info('Order Items seeded successfully!');
    }
}