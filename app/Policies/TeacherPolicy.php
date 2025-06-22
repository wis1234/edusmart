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
     * Determine whether the user can view the teacher.
     */
    public function view(User $user, Teacher $teacher): bool
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
        // Les school_admin peuvent voir uniquement les enseignants de leur école
        if ($user->role === 'school_admin' && $user->school_id) {
            return $teacher->school_id === $user->school_id;
        }
        return $user->hasAnyRole(['enseignant']);
    }

    /**
     * Determine whether the user can create teachers.
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
        
        // Seuls les admins et school_admin peuvent créer des enseignants
        return $user->hasPermissionTo('create teachers') || $user->role === 'school_admin';
    }

    /**
     * Determine whether the user can update the teacher.
     */
    public function update(User $user, Teacher $teacher): bool
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
        // Les school_admin peuvent modifier uniquement les enseignants de leur école
        if ($user->role === 'school_admin' && $user->school_id) {
            return $teacher->school_id === $user->school_id;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the teacher.
     */
    public function delete(User $user, Teacher $teacher): bool
    {
        if ($user->email === 'ronaldoagbohou@gmail.com') {
            return true;
        }
        
        // Vérifier le statut de l'enseignant
        if (($user->hasRole('teacher') || $user->hasRole('enseignant')) && 
            (!$user->teacherProfile || $user->teacherProfile->status !== 'active')) {
            return false;
        }
        
        if ($user->hasRole('admin')) {
            return true;
        }
        // Les school_admin peuvent supprimer uniquement les enseignants de leur école
        if ($user->role === 'school_admin' && $user->school_id) {
            return $teacher->school_id === $user->school_id;
        }
        return false;
    }

    /**
     * Determine whether the user can restore the teacher.
     */
    public function restore(User $user, Teacher $teacher): bool
    {
        if ($user->email === 'ronaldoagbohou@gmail.com') {
            return true;
        }
        
        // Vérifier le statut de l'enseignant
        if (($user->hasRole('teacher') || $user->hasRole('enseignant')) && 
            (!$user->teacherProfile || $user->teacherProfile->status !== 'active')) {
            return false;
        }
        
        if ($user->hasRole('admin')) {
            return true;
        }
        // Les school_admin peuvent restaurer uniquement les enseignants de leur école
        if ($user->role === 'school_admin' && $user->school_id) {
            return $teacher->school_id === $user->school_id;
        }
        return false;
    }

    /**
     * Determine whether the user can permanently delete the teacher.
     */
    public function forceDelete(User $user, Teacher $teacher): bool
    {
        if ($user->email === 'ronaldoagbohou@gmail.com') {
            return true;
        }
        
        // Vérifier le statut de l'enseignant
        if (($user->hasRole('teacher') || $user->hasRole('enseignant')) && 
            (!$user->teacherProfile || $user->teacherProfile->status !== 'active')) {
            return false;
        }
        
        if ($user->hasRole('admin')) {
            return true;
        }
        // Les school_admin peuvent supprimer définitivement uniquement les enseignants de leur école
        if ($user->role === 'school_admin' && $user->school_id) {
            return $teacher->school_id === $user->school_id;
        }
        return false;
    }
}
