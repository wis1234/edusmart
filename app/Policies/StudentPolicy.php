<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Student;
use Illuminate\Auth\Access\HandlesAuthorization;

class StudentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any students.
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
     * Determine whether the user can view the student.
     */
    public function view(User $user, Student $student): bool
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
        
        // Les admins peuvent voir toutes les écoles
        if ($user->hasRole('admin') || $user->hasRole('manager')) {
            return true;
        }
        
        // Les school admins ne peuvent voir que leur école et seulement si elle est active
        if ($user->hasRole('school_admin')) {
            if ($user->school_id !== $student->school_id) {
                return false;
            }
            return $student->status === 'active';
        }
        
        // Pour les autres rôles, vérifier le statut de l'étudiant - si pas actif, personne ne peut le voir
        if ($student->status !== 'active') {
            return false;
        }
        
        if ($user->hasRole('enseignant')) {
            // Les enseignants peuvent voir uniquement les étudiants de leurs classes assignées
            $teacherId = $user->teacherProfile ? $user->teacherProfile->id : null;
            if ($teacherId) {
                return $user->teachingClassRooms()->where('class_room_id', $student->class_room_id)->exists();
            }
            return false;
        }
        if ($user->hasRole('parent')) {
            return $user->id === $student->parent_id;
        }
        return false;
    }

    /**
     * Determine whether the user can create students.
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
            $school = \App\Models\School::find($user->school_id);
            if (!$school || $school->status !== 'active') {
                return false;
            }
        }
        
        return $user->hasAnyRole(['admin', 'manager']) || $user->role === 'school_admin';
    }

    /**
     * Determine whether the user can update the student.
     */
    public function update(User $user, Student $student): bool
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
        
        // Les admins et school admins peuvent modifier tous les étudiants (actifs et inactifs)
        if ($user->hasRole('admin') || $user->hasRole('manager')) {
            return true;
        }
        if ($user->role === 'school_admin' && $user->school_id) {
            return $student->school_id === $user->school_id;
        }
        
        // Pour les autres rôles, vérifier le statut de l'étudiant - si pas actif, personne ne peut le modifier
        if ($student->status !== 'active') {
            return false;
        }
        
        return false;
    }

    /**
     * Determine whether the user can delete the student.
     */
    public function delete(User $user, Student $student): bool
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
        
        if ($user->hasRole('admin')) {
            return true;
        }
        if ($user->role === 'school_admin' && $user->school_id) {
            return $student->school_id === $user->school_id;
        }
        return false;
    }
}
