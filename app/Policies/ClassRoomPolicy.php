<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ClassRoom;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClassRoomPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any class rooms.
     */
    public function viewAny(User $user)
    {
        return $user->hasAnyRole(['admin', 'manager', 'enseignant']);
    }

    /**
     * Determine whether the user can view the class room.
     */
    public function view(User $user, ClassRoom $classRoom)
    {
        if ($user->hasRole('admin') || $user->hasRole('manager')) {
            return true;
        }
        if ($user->hasRole('enseignant')) {
            return $user->id === $classRoom->teacher_id;
        }
        return false;
    }

    /**
     * Determine whether the user can create class rooms.
     */
    public function create(User $user)
    {
        return $user->hasAnyRole(['admin', 'manager']);
    }

    /**
     * Determine whether the user can update the class room.
     */
    public function update(User $user, ClassRoom $classRoom)
    {
        return $this->view($user, $classRoom);
    }

    /**
     * Determine whether the user can delete the class room.
     */
    public function delete(User $user, ClassRoom $classRoom)
    {
        return $user->hasRole('admin');
    }
}
