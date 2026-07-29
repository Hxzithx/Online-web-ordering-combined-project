<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerPolicy
{
    use HandlesAuthorization;

    /**
     * Admin override
     */
    public function before($user, $ability)
    {
        if ($user instanceof User && $user->role === 'admin') {
            return true;
        }
    }

    /**
     * View customer list
     * Staff/admin only
     */
    public function viewAny($user): bool
    {
        if ($user instanceof User) {
            return in_array($user->role, ['admin', 'steward']);
        }

        // customer can access own profile routes
        if ($user instanceof Customer) {
            return true;
        }

        return false;
    }

    /**
     * View single customer
     */
    public function view($user, Customer $customer): bool
    {
        if ($user instanceof User) {
            return in_array($user->role, ['admin', 'steward']);
        }

        if ($user instanceof Customer) {
            return (int)$user->id === (int)$customer->id;
        }

        return false;
    }

    /**
     * Create customer
     */
    public function create($user): bool
    {
        if ($user instanceof User) {
            return in_array($user->role, ['admin', 'steward']);
        }

        return false;
    }

    /**
     * Update customer
     */
    public function update($user, Customer $customer): bool
    {
        if ($user instanceof User) {
            return $user->role === 'admin';
        }

        if ($user instanceof Customer) {
            return (int)$user->id === (int)$customer->id;
        }

        return false;
    }

    /**
     * Delete customer
     */
    public function delete($user, Customer $customer): bool
    {
        if ($user instanceof User) {
            return $user->role === 'admin';
        }

        return false;
    }
}