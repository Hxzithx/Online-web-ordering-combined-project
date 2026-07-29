<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Customer;
use App\Models\OrderItem;
use App\Models\OrderItemAddOn;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderItemAddOnPolicy
{
    use HandlesAuthorization;

    /**
     * Admin bypass (users table)
     */
    public function before($authUser, $ability)
    {
        if ($authUser instanceof User && $authUser->role === 'admin') {
            return true;
        }

        return null;
    }

    /**
     * View add-ons list
     */
    public function viewAny($authUser, OrderItem $orderItem): bool
    {
        // STAFF / USER TABLE
        if ($authUser instanceof User) {
            return (int)$authUser->id === (int)$orderItem->order->opened_by ||
                   (int)$authUser->id === (int)$orderItem->order->steward_id;
        }

        // CUSTOMER TABLE
        if ($authUser instanceof Customer) {
            return (int)$authUser->id === (int)$orderItem->order->customer_id;
        }

        return false;
    }

    /**
     * View single add-on
     */
    public function view($authUser, OrderItemAddOn $orderItemAddOn): bool
    {
        if ($authUser instanceof User) {
            return (int)$authUser->id === (int)$orderItemAddOn->order->opened_by ||
                   (int)$authUser->id === (int)$orderItemAddOn->order->steward_id;
        }

        if ($authUser instanceof Customer) {
            return (int)$authUser->id === (int)$orderItemAddOn->order->customer_id;
        }

        return false;
    }

    /**
     * Create add-on
     */
    public function create($authUser, OrderItem $orderItem): bool
    {
        if (in_array($orderItem->order->status, ['completed', 'cancelled', 'closed'])) {
            return false;
        }

        if ($authUser instanceof User) {
            return (int)$authUser->id === (int)$orderItem->order->opened_by ||
                   (int)$authUser->id === (int)$orderItem->order->steward_id;
        }

        if ($authUser instanceof Customer) {
            return (int)$authUser->id === (int)$orderItem->order->customer_id;
        }

        return false;
    }

    /**
     * Update add-on
     */
    public function update($authUser, OrderItemAddOn $orderItemAddOn): bool
    {
        if ($authUser instanceof User) {
            return (int)$authUser->id === (int)$orderItemAddOn->order->opened_by ||
                   (int)$authUser->id === (int)$orderItemAddOn->order->steward_id;
        }

        if ($authUser instanceof Customer) {
            return (int)$authUser->id === (int)$orderItemAddOn->order->customer_id;
        }

        return false;
    }

    /**
     * Delete add-on
     */
    public function delete($authUser, OrderItemAddOn $orderItemAddOn): bool
    {
        if ($authUser instanceof User) {
            return (int)$authUser->id === (int)$orderItemAddOn->order->opened_by;
        }

        if ($authUser instanceof Customer) {
            return (int)$authUser->id === (int)$orderItemAddOn->order->customer_id;
        }

        return false;
    }
}