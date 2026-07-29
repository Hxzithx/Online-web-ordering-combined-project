<?php

namespace App\Policies;

use App\Models\Item;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ItemPolicy
{
    /**
     * Determine whether the user can view any models.
     * Required for ItemController@index
     */
    public function viewAny(Customer $customer): bool
    {
        // Allow both admins and staff to view the menu items
        return in_array($customer->role, ['user']);
    }

    /**
     * Determine whether the user can view the model.
     * Required for ItemController@show
     */
    public function view(Customer $customer, Item $item): bool
    {
        return in_array($customer->role, ['user']);
    }

    /**
     * Determine whether the user can create models.
     * Required for ItemController@store
     */
    public function create(User $user): bool
    {
        // Only admins should be able to add new items to the system
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can update the model.
     * Required for ItemController@update
     */
    public function update(User $user, Item $item): bool
    {
        // Only admins should be able to edit items
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can delete the model.
     * Required for ItemController@destroy
     */
    public function delete(User $user, Item $item): bool
    {
        // Only admins can delete items
        return $user->role === 'admin';
    }
}