<?php



namespace Database\Seeders;



use Illuminate\Database\Seeder;

use App\Models\OrderItemAddOn;

use App\Models\OrderItem;

use Illuminate\Support\Facades\DB;



class OrderItemAddOnSeeder extends Seeder

{

    public function run(): void
{
    $orderItem = OrderItem::first();
    $addOn = DB::table('add_ons')->first();

    // Add these lines to debug in your terminal
    if (!$orderItem) { $this->command->error("OrderItem table is empty!"); }
    if (!$addOn) { $this->command->error("Add_ons table is empty!"); }

    if ($orderItem && $addOn) {
        OrderItemAddOn::create([
            'order_id' => $orderItem->order_id,
            'order_item_id' => $orderItem->id,
            'add_on_id' => $addOn->id,
            'quantity' => 2,
            'unit_price' => 150.00,
            'total_price' => 300.00,
            'packaging_charge' => 10.00,
            'discount_amount' => 0,
            'tax_amount' => 30.00,
            'service_charge_amount' => 20.00,
            'total_amount' => 350.00,
        ]);
        $this->command->info("OrderItemAddOn created successfully!");
    }
}
}