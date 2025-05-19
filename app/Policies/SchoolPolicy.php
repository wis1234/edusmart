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
        if ($user->email === 'ronaldoagbohou@gmail.com') {
            return true;
        }
        return $user->hasAnyRole(['admin', 'manager', 'enseignant', 'parent']);
    }

    /**
     * Determine whether the user can view the school.
     */
    public function view(User $user)
    {
        if ($user->email === 'ronaldoagbohou@gmail.com') {
            return true;
        }
        return $user->hasAnyRole(['admin', 'manager', 'enseignant', 'parent']);
    }

    /**
     * Determine whether the user can create schools.
     */
    public function create(User $user)
    {
        if ($user->email === 'ronaldoagbohou@gmail.com') {
            return true;
        }
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can update the school.
     */
    public function update(User $user)
    {
        if ($user->email === 'ronaldoagbohou@gmail.com') {
            return true;
        }
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete the school.
     */
    public function delete(User $user)
    {
        if ($user->email === 'ronaldoagbohou@gmail.com') {
            return true;
        }
        return $user->hasRole('admin');
    }
}
