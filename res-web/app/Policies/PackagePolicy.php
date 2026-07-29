<?php

namespace App\Policies;

use App\Models\Package;
use App\Models\User;

class PackagePolicy
{
    /**
     * Determine whether the user can view any packages.
     */
    public function viewAny(User $user): bool
    {
        // Adjust logic based on your role/permission system
        return $user->tokenCan('view-packages') || $user->is_admin;
    }

    /**
     * Determine whether the user can view a specific package.
     */
    public function view(User $user, Package $package): bool
    {
        return $user->tokenCan('view-packages') || $user->is_admin;
    }

    /**
     * Determine whether the user can create packages.
     */
    public function create(User $user): bool
    {
        return $user->tokenCan('create-packages') || $user->is_admin;
    }

    /**
     * Determine whether the user can update the package.
     */
    public function update(User $user, Package $package): bool
    {
        return $user->tokenCan('update-packages') || $user->is_admin;
    }

    /**
     * Determine whether the user can delete the package.
     */
    public function delete(User $user, Package $package): bool
    {
        return $user->tokenCan('delete-packages') || $user->is_admin;
    }
}