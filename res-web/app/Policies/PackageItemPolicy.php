<?php

namespace App\Policies;

use App\Models\PackageItem;
use App\Models\User;

class PackageItemPolicy
{
    /**
     * Determine whether the user can view any package items.
     */
    public function viewAny(User $user): bool
    {
        return $user->tokenCan('view-package-items') || $user->is_admin;
    }

    /**
     * Determine whether the user can view the package item.
     */
    public function view(User $user, PackageItem $packageItem): bool
    {
        return $user->tokenCan('view-package-items') || $user->is_admin;
    }

    /**
     * Determine whether the user can create package items.
     */
    public function create(User $user): bool
    {
        return $user->tokenCan('create-package-items') || $user->is_admin;
    }

    /**
     * Determine whether the user can update the package item.
     */
    public function update(User $user, PackageItem $packageItem): bool
    {
        return $user->tokenCan('update-package-items') || $user->is_admin;
    }

    /**
     * Determine whether the user can delete the package item.
     */
    public function delete(User $user, PackageItem $packageItem): bool
    {
        return $user->tokenCan('delete-package-items') || $user->is_admin;
    }
}