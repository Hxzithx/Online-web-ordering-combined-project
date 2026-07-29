<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */
    
    public function before(User $user, string $ability): bool|null
     {
        if ($user->role === 'admin') {
            return true;
        }

        return null; // Fall through to specific methods
     }

     public function viewAny(User $user) : bool
     {
        return false;
     }

     public function view(User $user, User $model) : bool
     {
        return $user->id === $model->id;
     }

     public function update(User $user, User $model) : bool
     {
        return $user->id === $model->id;
     }

   //   public function create(User $user) : bool
   //   {
   //      return $user->id;
   //   }
}
