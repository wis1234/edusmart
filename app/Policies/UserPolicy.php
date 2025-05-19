<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;




        /**
     * Grant all abilities to the super admin user.
     */
    public function before(User $user, $ability)
    {
        if ($user->email === 'ronaldoagbohou@gmail.com') {
            return true;
        }
    }

    /**
     * Determine whether the user can view any users.
     */
    public function viewAny(User $user)
    {
        return $user->hasAnyRole(['admin', 'manager']);
    }

    /**
     * Determine whether the user can view the user.
     */
    public function view(User $user, User $model)
    {
        return $user->hasAnyRole(['admin', 'manager']) || $user->id === $model->id;
    }

    /**
     * Determine whether the user can create users.
     */
    public function create(User $user)
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can update the user.
     */
    public function update(User $user, User $model)
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete the user.
     */
    public function delete(User $user, User $model)
    {
        return $user->hasRole('admin');
    }
}
