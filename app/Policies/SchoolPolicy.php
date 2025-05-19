<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SchoolPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any schools.
     */
    public function viewAny(User $user)
    {
        return $user->hasAnyRole(['admin', 'manager']);
    }

    /**
     * Determine whether the user can view the school.
     */
    public function view(User $user)
    {
        return $user->hasAnyRole(['admin', 'manager']);
    }

    /**
     * Determine whether the user can create schools.
     */
    public function create(User $user)
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can update the school.
     */
    public function update(User $user)
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete the school.
     */
    public function delete(User $user)
    {
        return $user->hasRole('admin');
    }
}
