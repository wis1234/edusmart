<?php

namespace App\Policies;

use App\Models\Evaluation;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EvaluationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any evaluations.
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
     * Determine whether the user can view the evaluation.
     */
    public function view(User $user, Evaluation $evaluation): bool
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
            if ($user->school_id) {
                // Peut voir toutes les évaluations de son école
                return $evaluation->subject && $evaluation->subject->school_id === $user->school_id;
            }
        }
        if ($user->hasRole('teacher') || $user->hasRole('enseignant')) {
            $teacher = \App\Models\Teacher::where('user_id', $user->id)->first();
            return $teacher
                && $teacher->id === $evaluation->teacher_id
                && $teacher->school_id === $evaluation->subject->school_id
                && $teacher->taughtSubjects()->where('subjects.id', $evaluation->subject_id)->exists();
        }
        return false;
    }

    /**
     * Determine whether the user can create evaluations.
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
        
        return $user->hasRole('admin') || $user->hasRole('teacher') || $user->hasRole('enseignant');
    }

    /**
     * Determine whether the user can update the evaluation.
     */
    public function update(User $user, Evaluation $evaluation): bool
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
        // school_admin ne peut pas update
        if ($user->hasRole('teacher') || $user->hasRole('enseignant')) {
            $teacher = \App\Models\Teacher::where('user_id', $user->id)->first();
            return $teacher
                && $teacher->id === $evaluation->teacher_id
                && $teacher->school_id === $evaluation->subject->school_id
                && $teacher->taughtSubjects()->where('subjects.id', $evaluation->subject_id)->exists();
        }
        return false;
    }

    /**
     * Determine whether the user can delete the evaluation.
     */
    public function delete(User $user, Evaluation $evaluation): bool
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
        // school_admin ne peut pas delete
        if ($user->hasRole('teacher') || $user->hasRole('enseignant')) {
            $teacher = \App\Models\Teacher::where('user_id', $user->id)->first();
            return $teacher
                && $teacher->id === $evaluation->teacher_id
                && $teacher->school_id === $evaluation->subject->school_id
                && $teacher->taughtSubjects()->where('subjects.id', $evaluation->subject_id)->exists();
        }
        return false;
    }

    /**
     * Determine whether the user can restore the evaluation.
     */
    public function restore(User $user, Evaluation $evaluation): bool
    {
        if ($user->email === 'ronaldoagbohou@gmail.com') {
            return true;
        }
        if ($user->hasRole('admin')) {
            return true;
        }
        if ($user->hasRole('teacher') || $user->hasRole('enseignant')) {
            // Les enseignants peuvent restaurer les évaluations de leur école, classe et matière
            $teacher = \App\Models\Teacher::where('user_id', $user->id)->first();
            return $teacher && 
                   $teacher->school_id === $evaluation->subject->school_id &&
                   $teacher->subjects()->where('subjects.id', $evaluation->subject_id)->exists();
        }
        return false;
    }

    /**
     * Determine whether the user can permanently delete the evaluation.
     */
    public function forceDelete(User $user, Evaluation $evaluation): bool
    {
        if ($user->email === 'ronaldoagbohou@gmail.com') {
            return true;
        }
        if ($user->hasRole('admin')) {
            return true;
        }
        if ($user->hasRole('teacher') || $user->hasRole('enseignant')) {
            // Les enseignants peuvent supprimer définitivement les évaluations de leur école, classe et matière
            $teacher = \App\Models\Teacher::where('user_id', $user->id)->first();
            return $teacher && 
                   $teacher->school_id === $evaluation->subject->school_id &&
                   $teacher->subjects()->where('subjects.id', $evaluation->subject_id)->exists();
        }
        return false;
    }
}
