<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ParentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any parents.
     */
    public function viewAny(User $user): bool
    {
        if ($user->email === 'ronaldoagbohou@gmail.com') {
            return true;
        }
        return $user->hasRole('admin') || $user->hasRole('parent');
    }

    /**
     * Determine whether the user can view the parent.
     */
    public function view(User $user, User $parent): bool
    {
        if ($user->email === 'ronaldoagbohou@gmail.com') {
            return true;
        }
        return $user->hasRole('admin') || $user->hasRole('parent');
    }

    /**
     * Determine whether the user can create parents.
     */
    public function create(User $user): bool
    {
        if ($user->email === 'ronaldoagbohou@gmail.com') {
            return true;
        }
        return $user->hasRole('admin') || $user->hasRole('parent');
    }

    /**
     * Determine whether the user can update the parent.
     */
    public function update(User $user, User $parent): bool
    {
        if ($user->email === 'ronaldoagbohou@gmail.com') {
            return true;
        }
        return $user->hasRole('admin') || $user->hasRole('parent');
    }

    /**
     * Determine whether the user can delete the parent.
     */
    public function delete(User $user, User $parent): bool
    {
        if ($user->email === 'ronaldoagbohou@gmail.com') {
            return true;
        }
        return $user->hasRole('admin') || $user->hasRole('parent');
    }
}
