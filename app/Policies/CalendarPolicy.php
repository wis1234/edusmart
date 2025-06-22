<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Calendar;
use Illuminate\Auth\Access\HandlesAuthorization;

class CalendarPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any calendars.
     */
    public function viewAny(User $user)
    {
        if ($user->email === 'ronaldoagbohou@gmail.com') {
            return true;
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
     * Determine whether the user can view the calendar.
     */
    public function view(User $user, Calendar $calendar)
    {
        if ($user->email === 'ronaldoagbohou@gmail.com') {
            return true;
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
     * Determine whether the user can create calendars.
     */
    public function create(User $user)
    {
        if ($user->email === 'ronaldoagbohou@gmail.com') {
            return true;
        }
        
        // Vérifier le statut de l'école pour les school admins
        if ($user->role === 'school_admin' && $user->school_id) {
            $school = \App\Models\School::find($user->school_id);
            if (!$school || $school->status !== 'active') {
                return false;
            }
        }
        
        return $user->hasAnyRole(['admin', 'manager']) || $user->role === 'school_admin';
    }

    /**
     * Determine whether the user can update the calendar.
     */
    public function update(User $user, Calendar $calendar)
    {
        if ($user->email === 'ronaldoagbohou@gmail.com') {
            return true;
        }
        
        // Vérifier le statut de l'école pour les school admins
        if ($user->role === 'school_admin' && $user->school_id) {
            $school = \App\Models\School::find($user->school_id);
            if (!$school || $school->status !== 'active') {
                return false;
            }
        }
        
        return $user->hasAnyRole(['admin', 'manager']) || $user->role === 'school_admin';
    }

    /**
     * Determine whether the user can delete the calendar.
     */
    public function delete(User $user, Calendar $calendar)
    {
        if ($user->email === 'ronaldoagbohou@gmail.com') {
            return true;
        }
        
        // Vérifier le statut de l'école pour les school admins
        if ($user->role === 'school_admin' && $user->school_id) {
            $school = \App\Models\School::find($user->school_id);
            if (!$school || $school->status !== 'active') {
                return false;
            }
        }
        
        return $user->hasRole('admin') || $user->role === 'school_admin';
    }
}
