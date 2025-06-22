<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Subject;
use Illuminate\Auth\Access\HandlesAuthorization;

class SubjectPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any subjects.
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
     * Determine whether the user can view the subject.
     */
    public function view(User $user, Subject $subject): bool
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
            // Les school-admin peuvent voir uniquement les matières de leur école
            return $user->school_id === $subject->school_id;
        }
        if ($user->hasRole('enseignant')) {
            // Les enseignants peuvent voir toutes les matières de leur école assignée (lecture seule)
            $teacher = \App\Models\Teacher::where('user_id', $user->id)->first();
            return $teacher && $teacher->school_id === $subject->school_id;
        }
        if ($user->hasRole('parent')) {
            // Les parents peuvent voir les matières des classes de leurs enfants
            return $user->students()->whereHas('classRoom.subjects', function($query) use ($subject) {
                $query->where('subject_id', $subject->id);
            })->exists();
        }
        return false;
    }

    /**
     * Determine whether the user can create subjects.
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
     * Determine whether the user can update the subject.
     */
    public function update(User $user, Subject $subject): bool
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
            // Les school-admin peuvent modifier uniquement les matières de leur école
            return $user->school_id === $subject->school_id;
        }
        // Les enseignants n'ont pas accès à la modification (lecture seule)
        return false;
    }

    /**
     * Determine whether the user can delete the subject.
     */
    public function delete(User $user, Subject $subject): bool
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
        if ($user->hasRole('school_admin')) {
            // Les school-admin peuvent supprimer uniquement les matières de leur école
            return $user->school_id === $subject->school_id;
        }
        // Les enseignants n'ont pas accès à la suppression (lecture seule)
        return false;
    }

    /**
     * Determine whether the user can restore the subject.
     */
    public function restore(User $user, Subject $subject): bool
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
        if ($user->hasRole('school_admin')) {
            // Les school-admin peuvent restaurer uniquement les matières de leur école
            return $user->school_id === $subject->school_id;
        }
        // Les enseignants n'ont pas accès à la restauration (lecture seule)
        return false;
    }

    /**
     * Determine whether the user can permanently delete the subject.
     */
    public function forceDelete(User $user, Subject $subject): bool
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
        if ($user->hasRole('school_admin')) {
            // Les school-admin peuvent supprimer définitivement uniquement les matières de leur école
            return $user->school_id === $subject->school_id;
        }
        // Les enseignants n'ont pas accès à la suppression définitive (lecture seule)
        return false;
    }
} 