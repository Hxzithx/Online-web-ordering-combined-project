<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderItemPolicy
{
    use HandlesAuthorization;

    /**
     * Admin bypass
     */
    public function before($authUser, $ability)
    {
        if ($authUser instanceof User && $authUser->role === 'admin') {
            return true;
        }

        return null;
    }

    /**
     * View items
     */
    public function viewAny($authUser, OrderItem $orderItem): bool
    {
        if ($authUser instanceof User) {
            return (int)$authUser->id === (int)$orderItem->opened_by ||
                   (int)$authUser->id === (int)$orderItem->steward_id ||
                   (int)$authUser->id === (int)$orderItem->user_id;
        }

        if ($authUser instanceof Customer) {
            return (int)$authUser->id === (int)$orderItem->order->customer_id;
        }

        return false;
    }

    /**
     * Create item
     */
    public function create($authUser, Order $order): bool
    {
        if (in_array($order->status, ['completed', 'cancelled', 'closed'])) {
            return false;
        }

        if ($authUser instanceof User) {
            return (int)$authUser->id === (int)$order->opened_by ||
                   (int)$authUser->id === (int)$order->steward_id;
        }

        if ($authUser instanceof Customer) {
            return (int)$authUser->id === (int)$order->customer_id;
        }

        return false;
    }

    /**
     * Update item
     */
    public function update($authUser, OrderItem $orderItem): bool
    {
        if ($authUser instanceof User) {
            return (int)$authUser->id === (int)$orderItem->opened_by ||
                   (int)$authUser->id === (int)$orderItem->steward_id;
        }

        if ($authUser instanceof Customer) {
            return (int)$authUser->id === (int)$orderItem->order->customer_id;
        }

        return false;
    }

    /**
     * Delete item
     */
    public function delete($authUser, OrderItem $orderItem): bool
    {
        if ($authUser instanceof User) {
            return (int)$authUser->id === (int)$orderItem->opened_by;
        }

        if ($authUser instanceof Customer) {
            return (int)$authUser->id === (int)$orderItem->order->customer_id;
        }

        return false;
    }
}