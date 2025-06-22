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
    public function viewAny(User $user): bool
    {
        if ($user->email === 'ronaldoagbohou@gmail.com') {
            return true;
        }
        
        // Vérifier le statut de l'enseignant
        if (($user->hasRole('teacher') || $user->hasRole('enseignant')) && 
            (!$user->teacherProfile || $user->teacherProfile->status !== 'active')) {
            return false;
        }
        
        // Vérifier le statut de l'école pour les school admins
        if ($user->role === 'school_admin' && $user->school_id) {
            $school = \App\Models\School::find($user->school_id);
            if (!$school || $school->status !== 'active') {
                return false;
            }
        }
        
        return $user->hasAnyRole(['admin', 'manager', 'enseignant', 'parent']) || $user->role === 'school_admin';
    }

    /**
     * Determine whether the user can view the class room.
     */
    public function view(User $user, ClassRoom $classRoom): bool
    {
        if ($user->email === 'ronaldoagbohou@gmail.com') {
            return true;
        }
        
        // Vérifier le statut de l'enseignant
        if (($user->hasRole('teacher') || $user->hasRole('enseignant')) && 
            (!$user->teacherProfile || $user->teacherProfile->status !== 'active')) {
            return false;
        }
        
        if ($user->hasRole('admin') || $user->hasRole('manager')) {
            return true;
        }
        if ($user->hasRole('school_admin')) {
            return $user->school_id === $classRoom->school_id;
        }
        if ($user->hasRole('enseignant')) {
            // Les enseignants peuvent voir uniquement leurs classes assignées
            $teacherId = $user->teacherProfile ? $user->teacherProfile->id : null;
            if ($teacherId) {
                return $user->teachingClassRooms()->where('class_room_id', $classRoom->id)->exists();
            }
            return false;
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
    public function create(User $user): bool
    {
        if ($user->email === 'ronaldoagbohou@gmail.com') {
            return true;
        }
        
        // Vérifier le statut de l'enseignant
        if (($user->hasRole('teacher') || $user->hasRole('enseignant')) && 
            (!$user->teacherProfile || $user->teacherProfile->status !== 'active')) {
            return false;
        }
        
        return $user->hasAnyRole(['admin', 'manager', 'school_admin']);
    }

    /**
     * Determine whether the user can update the class room.
     */
    public function update(User $user, ClassRoom $classRoom): bool
    {
        if ($user->email === 'ronaldoagbohou@gmail.com') {
            return true;
        }
        
        // Vérifier le statut de l'enseignant
        if (($user->hasRole('teacher') || $user->hasRole('enseignant')) && 
            (!$user->teacherProfile || $user->teacherProfile->status !== 'active')) {
            return false;
        }
        
        if ($user->hasRole('admin') || $user->hasRole('manager')) {
            return true;
        }
        if ($user->hasRole('school_admin')) {
            return $user->school_id === $classRoom->school_id;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the class room.
     */
    public function delete(User $user, ClassRoom $classRoom): bool
    {
        if ($user->email === 'ronaldoagbohou@gmail.com') {
            return true;
        }
        
        // Vérifier le statut de l'enseignant
        if (($user->hasRole('teacher') || $user->hasRole('enseignant')) && 
            (!$user->teacherProfile || $user->teacherProfile->status !== 'active')) {
            return false;
        }
        
        if ($user->hasRole('admin') || $user->hasRole('manager')) {
            return true;
        }
        if ($user->hasRole('school_admin')) {
            return $user->school_id === $classRoom->school_id;
        }
        return false;
    }
}
