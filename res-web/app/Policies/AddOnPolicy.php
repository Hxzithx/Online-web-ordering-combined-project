<?php

namespace App\Policies;

use App\Models\AddOn;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AddOnPolicy
{
    use HandlesAuthorization;

    /**
     * Admin bypass - admin can do everything
     */
    // public function before(User $user, $ability): bool|null
    // {
    //     if ($user->role === 'admin') {
    //         return true;
    //     }

    //     return null; // Let other methods decide for non-admins
    // }

    /**
     * Everyone can view all addons — needed for order form
     */
    public function viewAny(Customer $customer): bool
    {
        return in_array($customer->role, ['user']);
    }

    /**
     * Everyone can view a single addon
     */
    public function view(Customer $customer, AddOn $addOn): bool
    {
        return in_array($customer->role, ['user']);
    }

    /**
     * Only admin can create addons
     * (handled by before() for admin)
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Only admin can update addons
     * (handled by before() for admin)
     */
    public function update(User $user, AddOn $addOn): bool
    {
        return false;
    }

    /**
     * Only admin can delete addons
     * (handled by before() for admin)
     */
    public function delete(User $user, AddOn $addOn): bool
    {
        return false;
    }
}