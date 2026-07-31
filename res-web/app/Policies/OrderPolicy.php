<?php

namespace App\Policies;

use App\Models\Order;

class OrderPolicy
{
    /**
     * Temporarily allow all operations for all users
     */
    public function before($user = null, $ability = null)
    {
        return true;
    }

    public function viewAny($user = null): bool
    {
        return true;
    }

    public function view($user = null, Order $order = null): bool
    {
        return true;
    }

    public function create($user = null): bool
    {
        return true;
    }

    public function update($user = null, Order $order = null): bool
    {
        return true;
    }

    public function delete($user = null, Order $order = null): bool
    {
        return true;
    }

    public function restore($user = null, Order $order = null): bool
    {
        return true;
    }

    public function forceDelete($user = null, Order $order = null): bool
    {
        return true;
    }
}