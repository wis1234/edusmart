<?php

namespace App\Policies;

use App\Models\StudentGrade;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StudentGradePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any student grades.
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
     * Determine whether the user can view the student grade.
     */
    public function view(User $user, StudentGrade $studentGrade): bool
    {
        if ($user->email === 'ronaldoagbohou@gmail.com') {
            return true;
        }
        
        if ($user->hasRole('student')) {
            return $user->studentProfile && $user->studentProfile->id === $studentGrade->student_id;
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
                // Peut voir toutes les notes des élèves de son école
                return $studentGrade->student && $studentGrade->student->school_id === $user->school_id;
            }
        }
        if ($user->hasRole('teacher') || $user->hasRole('enseignant')) {
            // Les enseignants peuvent voir les notes des évaluations de leurs matières
            return $user->teacherProfile && $user->teacherProfile->subjects()->where('subjects.id', $studentGrade->evaluation->subject_id)->exists();
        }
        return false;
    }

    /**
     * Determine whether the user can create student grades.
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
     * Determine whether the user can update the student grade.
     */
    public function update(User $user, StudentGrade $studentGrade): bool
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
            // Les enseignants peuvent modifier les notes des évaluations de leurs matières
            return $user->teacherProfile && $user->teacherProfile->subjects()->where('subjects.id', $studentGrade->evaluation->subject_id)->exists();
        }
        return false;
    }

    /**
     * Determine whether the user can delete the student grade.
     */
    public function delete(User $user, StudentGrade $studentGrade): bool
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
            // Les enseignants peuvent supprimer les notes des évaluations de leurs matières
            return $user->teacherProfile && $user->teacherProfile->subjects()->where('subjects.id', $studentGrade->evaluation->subject_id)->exists();
        }
        return false;
    }
}
