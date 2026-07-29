<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\Portion;
use App\Models\User;

class ExtraServicePolicy
{
    /**
     * Admin and staff can view all portions
     */
    public function viewAny(Customer $customer): bool
    {
        return in_array($customer->role, ['user']);
    }

    /**
     * Admin and staff can view a single portion
     */
    public function view(Customer $customer, Portion $portion): bool
    {
        return in_array($customer->role, ['user']);
    }

    /**
     * Only admin can create portions
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Only admin can update portions
     */
    public function update(User $user, Portion $portion): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Only admin can delete portions
     */
    public function delete(User $user, Portion $portion): bool
    {
        return $user->role === 'admin';
    }
}