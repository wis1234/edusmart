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
        // Vérifier le statut de l'école pour les school admins
        if ($user->role === 'school_admin' && $user->school_id) {
            $school = \App\Models\School::find($user->school_id);
            if (!$school || $school->status !== 'active') {
                return false;
            }
        }
        
        return $user->hasRole('admin') || $user->role === 'admin' || $user->role === 'school_admin';
    }

    /**
     * Determine whether the user can view the user.
     */
    public function view(User $user, User $model)
    {
        // Vérifier le statut de l'école pour les school admins
        if ($user->role === 'school_admin' && $user->school_id) {
            $school = \App\Models\School::find($user->school_id);
            if (!$school || $school->status !== 'active') {
                return false;
            }
        }
        
        // Les admins peuvent voir tous les utilisateurs
        if ($user->hasRole('admin') || $user->role === 'admin') {
            return true;
        }
        
        // Les school_admin peuvent voir les parents des étudiants de leur école
        if ($user->role === 'school_admin' && $user->school_id) {
            if ($model->hasRole('parent')) {
                return $model->students()->where('school_id', $user->school_id)->exists();
            }
        }
        
        // Les utilisateurs peuvent voir leur propre profil
        return $user->id === $model->id;
    }

    /**
     * Determine whether the user can create users.
     */
    public function create(User $user)
    {
        // Vérifier le statut de l'école pour les school admins
        if ($user->role === 'school_admin' && $user->school_id) {
            $school = \App\Models\School::find($user->school_id);
            if (!$school || $school->status !== 'active') {
                return false;
            }
        }
        
        return $user->hasRole('admin') || $user->role === 'admin' || $user->role === 'school_admin';
    }

    /**
     * Determine whether the user can update the user.
     */
    public function update(User $user, User $model)
    {
        // Vérifier le statut de l'école pour les school admins
        if ($user->role === 'school_admin' && $user->school_id) {
            $school = \App\Models\School::find($user->school_id);
            if (!$school || $school->status !== 'active') {
                return false;
            }
        }
        
        // Les admins peuvent modifier tous les utilisateurs
        if ($user->hasRole('admin') || $user->role === 'admin') {
            return true;
        }
        
        // Les school_admin peuvent modifier les parents des étudiants de leur école
        if ($user->role === 'school_admin' && $user->school_id) {
            if ($model->hasRole('parent')) {
                return $model->students()->where('school_id', $user->school_id)->exists();
            }
        }
        
        return false;
    }

    /**
     * Determine whether the user can delete the user.
     */
    public function delete(User $user, User $model)
    {
        // Vérifier le statut de l'école pour les school admins
        if ($user->role === 'school_admin' && $user->school_id) {
            $school = \App\Models\School::find($user->school_id);
            if (!$school || $school->status !== 'active') {
                return false;
            }
        }
        
        // Les admins peuvent supprimer tous les utilisateurs
        if ($user->hasRole('admin') || $user->role === 'admin') {
            return true;
        }
        
        // Les school_admin peuvent supprimer les parents des étudiants de leur école
        if ($user->role === 'school_admin' && $user->school_id) {
            if ($model->hasRole('parent')) {
                return $model->students()->where('school_id', $user->school_id)->exists();
            }
        }
        
        return false;
    }
}
