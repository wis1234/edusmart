<?php

namespace App\Policies;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeacherPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any teachers.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view teachers');
    }

    /**
     * Determine whether the user can view the teacher.
     */
    public function view(User $user, Teacher $teacher): bool
    {
        return $user->hasPermissionTo('view teachers');
    }

    /**
     * Determine whether the user can create teachers.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create teachers');
    }

    /**
     * Determine whether the user can update the teacher.
     */
    public function update(User $user, Teacher $teacher): bool
    {
        return $user->hasPermissionTo('edit teachers');
    }

    /**
     * Determine whether the user can delete the teacher.
     */
    public function delete(User $user, Teacher $teacher): bool
    {
        return $user->hasPermissionTo('delete teachers');
    }

    /**
     * Determine whether the user can restore the teacher.
     */
    public function restore(User $user, Teacher $teacher): bool
    {
        return $user->hasPermissionTo('edit teachers');
    }

    /**
     * Determine whether the user can permanently delete the teacher.
     */
    public function forceDelete(User $user, Teacher $teacher): bool
    {
        return $user->hasPermissionTo('delete teachers');
    }
}
