<?php

namespace App\Policies;

use App\Models\ItemPrice;
use App\Models\Customer;

class ItemPricePolicy
{
    /**
     * View all item prices
     */
    public function viewAny(Customer $customer): bool
    {
        return in_array($customer->role, ['user']);
    }

    /**
     * View single item price
     */
    public function view(Customer $customer, ItemPrice $itemPrice): bool
    {
        return in_array($customer->role, ['user']);
    }

    /**
     * Create item price
     */
    public function create(Customer $customer): bool
    {
        return in_array($customer->role, ['admin', 'staff']);
    }

    /**
     * Update item price
     */
    public function update(Customer $customer, ItemPrice $itemPrice): bool
    {
        return in_array($customer->role, ['admin', 'staff']);
    }

    /**
     * Delete item price
     */
    public function delete(Customer $customer, ItemPrice $itemPrice): bool
    {
        return $customer->role === 'admin';
    }
}