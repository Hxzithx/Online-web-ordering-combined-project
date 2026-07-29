<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use App\Models\Customer;

class OrderPolicy
{
    /**
     * Admin can do everything automatically
     */
    public function before(User|Customer $user, $ability)
    {
        if ($user instanceof User && $user->role === 'admin') {
            return true;
        }
    }

    /**
     * View all orders
     * Admin = yes
     * Customer = yes (their own orders list)
     */
    public function viewAny(User|Customer $user): bool
    {
        return true;
    }

    /**
     * View single order
     * Admin = yes (handled by before)
     * Customer = only own order
     * User staff = own opened orders
     */
    public function view(User|Customer $user, Order $order): bool
    {
        if ($user instanceof Customer) {
            return $order->customer_id === $user->id;
        }

        return $order->opened_by === $user->id;
    }

    /**
     * Create order
     * Customer can create
     * Admin can create (handled by before)
     */
    public function create(User|Customer $user): bool
    {
        return true;
    }

    /**
     * Update order
     * Customer can update own pending/open orders only
     * Admin = yes
     */
    public function update(User|Customer $user, Order $order): bool
    {
        if ($user instanceof Customer) {
            return $order->customer_id === $user->id &&
                   in_array($order->status, ['pending', 'open']);
        }

        return $order->opened_by === $user->id;
    }

    /**
     * Delete order
     * Customer can delete own pending order only
     * Admin = yes
     */
    public function delete(User|Customer $user, Order $order): bool
    {
        if ($user instanceof Customer) {
            return $order->customer_id === $user->id &&
                   $order->status === 'pending';
        }

        return false;
    }

    public function restore(User|Customer $user, Order $order): bool
    {
        return false;
    }

    public function forceDelete(User|Customer $user, Order $order): bool
    {
        return false;
    }
}