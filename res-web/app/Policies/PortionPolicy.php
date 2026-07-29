<?php

namespace App\Policies;

use App\Models\Portion;
use App\Models\User;
use App\Models\Customer;

class PortionPolicy
{
    public function viewAny(User|Customer $user): bool
    {
        return true;
    }

    public function view(User|Customer $user, Portion $portion): bool
    {
        return true;
    }

    public function create(User|Customer $user): bool
    {
        return $user instanceof User && $user->role === 'admin';
    }

    public function update(User|Customer $user, Portion $portion): bool
    {
        return $user instanceof User && $user->role === 'admin';
    }

    public function delete(User|Customer $user, Portion $portion): bool
    {
        return $user instanceof User && $user->role === 'admin';
    }
}