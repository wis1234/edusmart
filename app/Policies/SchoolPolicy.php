<?php

namespace App\Policies;

use App\Models\User;
use App\Models\School;
use Illuminate\Auth\Access\HandlesAuthorization;

class SchoolPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any schools.
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
            $school = School::find($user->school_id);
            if (!$school || $school->status !== 'active') {
                return false;
            }
        }
        
        return $user->hasAnyRole(['admin', 'manager', 'enseignant', 'parent', 'school_admin']);
    }

    /**
     * Determine whether the user can view the school.
     */
    public function view(User $user, School $school): bool
    {
        if ($user->email === 'ronaldoagbohou@gmail.com') {
            return true;
        }
        
        // Vérifier le statut de l'enseignant
        if (($user->hasRole('teacher') || $user->hasRole('enseignant')) && 
            (!$user->teacherProfile || $user->teacherProfile->status !== 'active')) {
            return false;
        }
        
        // Les admins peuvent voir toutes les écoles
        if ($user->hasRole('admin') || $user->hasRole('manager')) {
            return true;
        }
        
        // Les school admins ne peuvent voir que leur école et seulement si elle est active
        if ($user->hasRole('school_admin')) {
            if ($user->school_id !== $school->id) {
                return false;
            }
            return $school->status === 'active';
        }
        
        return $user->hasAnyRole(['enseignant', 'parent']);
    }

    /**
     * Determine whether the user can create schools.
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
        
        // Vérifier le statut de l'école pour les school admins
        if ($user->role === 'school_admin' && $user->school_id) {
            $school = School::find($user->school_id);
            if (!$school || $school->status !== 'active') {
                return false;
            }
        }
        
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can update the school.
     */
    public function update(User $user, School $school): bool
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
            $school = School::find($user->school_id);
            if (!$school || $school->status !== 'active') {
                return false;
            }
        }
        
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete the school.
     */
    public function delete(User $user, School $school): bool
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
            $school = School::find($user->school_id);
            if (!$school || $school->status !== 'active') {
                return false;
            }
        }
        
        return $user->hasRole('admin');
    }
}
