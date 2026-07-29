<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | STAFF USERS
        |--------------------------------------------------------------------------
        */

        $admin = User::firstOrCreate(
            ['email' => 'manager@resto.com'],
            [
                'first_name' => 'Hasitha',
                'last_name'  => 'Lakruwan',
                'phone'      => '0760700354',
                'address'    => 'Walapane',
                'role'       => 'admin',
                'password'   => Hash::make('password'),
                'status'     => 'active',
            ]
        );

        $steward = User::firstOrCreate(
            ['email' => 'kamal@resto.com'],
            [
                'first_name' => 'Kamal',
                'last_name'  => 'Perera',
                'phone'      => '0771234567',
                'address'    => 'Kandy',
                'role'       => 'steward',
                'password'   => Hash::make('password'),
                'status'     => 'active',
            ]
        );

        $cashier = User::firstOrCreate(
            ['email' => 'cashier@resto.com'],
            [
                'first_name' => 'Nimali',
                'last_name'  => 'Fernando',
                'phone'      => '0711111111',
                'address'    => 'Colombo',
                'role'       => 'cashier',
                'password'   => Hash::make('password'),
                'status'     => 'active',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | EXISTING CUSTOMER
        |--------------------------------------------------------------------------
        */

        $customer = Customer::find(2);

        if (!$customer) {
            $customer = Customer::firstOrCreate(
                ['email' => 'manjula@gmail.com'],
                [
                    'first_name' => 'Manjula',
                    'last_name'  => 'Weerasoriye',
                    'phone'      => '0522279473',
                    'address'    => 'Walapane',
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | ORDER 1 - PENDING ONLINE ORDER
        |--------------------------------------------------------------------------
        */

        Order::firstOrCreate(
            ['order_number' => 'WEB-' . date('Ymd') . '-001'],
            [
                'branch_id'       => 1,
                'order_number'    => 'WEB-' . date('Ymd') . '-001',
                'table_id'        => null,
                'order_type_id'   => 2,

                'user_id'         => $admin->id,
                'customer_id'     => $customer->id,

                'steward_id'      => $steward->id,
                'opened_by'       => $admin->id,
                'cashier_id'      => null,
                'closed_by'       => null,

                'customer_name'   => $customer->first_name . ' ' . $customer->last_name,

                'status'          => 'pending',

                'total_amount'    => 2450.00,
                'tax_amount'      => 245.00,
                'service_charge'  => 0.00,

                'payment_status'  => 'pending',
                'payment_method'  => 'cash',

                'opened_at'       => Carbon::now()->subMinutes(20),
                'closed_at'       => null,

                'created_at'      => now(),
                'updated_at'      => now(),
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | ORDER 2 - COMPLETED DINE IN
        |--------------------------------------------------------------------------
        */

        Order::firstOrCreate(
            ['order_number' => 'DIN-' . date('Ymd') . '-002'],
            [
                'branch_id'       => 1,
                'order_number'    => 'DIN-' . date('Ymd') . '-002',
                'table_id'        => 4,
                'order_type_id'   => 1,

                'user_id'         => $admin->id,
                'customer_id'     => $customer->id,

                'steward_id'      => $steward->id,
                'opened_by'       => $steward->id,
                'cashier_id'      => $cashier->id,
                'closed_by'       => $cashier->id,

                'customer_name'   => $customer->first_name . ' ' . $customer->last_name,

                'status'          => 'completed',

                'total_amount'    => 5200.00,
                'tax_amount'      => 520.00,
                'service_charge'  => 260.00,

                'payment_status'  => 'paid',
                'payment_method'  => 'card',

                'opened_at'       => Carbon::now()->subHours(3),
                'closed_at'       => Carbon::now()->subHours(2),

                'created_at'      => now(),
                'updated_at'      => now(),
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | ORDER 3 - SERVED / WAITING PAYMENT
        |--------------------------------------------------------------------------
        */

        Order::firstOrCreate(
            ['order_number' => 'LUN-' . date('Ymd') . '-003'],
            [
                'branch_id'       => 1,
                'order_number'    => 'LUN-' . date('Ymd') . '-003',
                'table_id'        => 7,
                'order_type_id'   => 1,

                'user_id'         => $admin->id,
                'customer_id'     => $customer->id,

                'steward_id'      => $steward->id,
                'opened_by'       => $steward->id,
                'cashier_id'      => null,
                'closed_by'       => null,

                'customer_name'   => $customer->first_name . ' ' . $customer->last_name,

                'status'          => 'served',

                'total_amount'    => 1800.00,
                'tax_amount'      => 180.00,
                'service_charge'  => 90.00,

                'payment_status'  => 'pending',
                'payment_method'  => 'cash',

                'opened_at'       => Carbon::now()->subHour(),
                'closed_at'       => null,

                'created_at'      => now(),
                'updated_at'      => now(),
            ]
        );

        $this->command->info('3 customer orders seeded successfully.');
    }
}