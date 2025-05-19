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
        if ($user->email === 'ronaldoagbohou@gmail.com') {
            return true;
        }
        return $user->hasAnyRole(['admin', 'manager', 'enseignant', 'parent']);
    }

    /**
     * Determine whether the user can view the class room.
     */
    public function view(User $user, ClassRoom $classRoom)
    {
        if ($user->email === 'ronaldoagbohou@gmail.com') {
            return true;
        }
        if ($user->hasRole('admin') || $user->hasRole('manager')) {
            return true;
        }
        if ($user->hasRole('enseignant')) {
            return $user->id === $classRoom->teacher_id;
        }
        if ($user->hasRole('parent')) {
            // Parent can view class rooms of their children
            return $user->students()->whereHas('classRoom', function($query) use ($classRoom) {
                $query->where('id', $classRoom->id);
            })->exists();
        }
        return false;
    }

    /**
     * Determine whether the user can create class rooms.
     */
    public function create(User $user)
    {
        if ($user->email === 'ronaldoagbohou@gmail.com') {
            return true;
        }
        return $user->hasAnyRole(['admin', 'manager']);
    }

    /**
     * Determine whether the user can update the class room.
     */
    public function update(User $user, ClassRoom $classRoom)
    {
        if ($user->email === 'ronaldoagbohou@gmail.com') {
            return true;
        }
        return $this->view($user, $classRoom);
    }

    /**
     * Determine whether the user can delete the class room.
     */
    public function delete(User $user, ClassRoom $classRoom)
    {
        if ($user->email === 'ronaldoagbohou@gmail.com') {
            return true;
        }
        return $user->hasRole('admin');
    }
}
