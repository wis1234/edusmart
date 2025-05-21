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
        return $user->hasRole('admin') || $user->hasRole('teacher') || $user->hasRole('enseignant') || $user->email === 'ronaldoagbohou@gmail.com';
    }

    /**
     * Determine whether the user can view the student grade.
     */
    public function view(User $user, StudentGrade $studentGrade): bool
    {
        if ($user->email === 'ronaldoagbohou@gmail.com') {
            return true;
        }
        return $user->hasRole('admin') ||
               ($user->hasRole('teacher') && $studentGrade->evaluation->teacher_id === $user->id) ||
               ($user->hasRole('enseignant') && $studentGrade->evaluation->teacher_id === $user->id);
    }

    /**
     * Determine whether the user can create student grades.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('teacher') || $user->hasRole('enseignant') || $user->email === 'ronaldoagbohou@gmail.com';
    }

    /**
     * Determine whether the user can update the student grade.
     */
    public function update(User $user, StudentGrade $studentGrade): bool
    {
        if ($user->email === 'ronaldoagbohou@gmail.com') {
            return true;
        }
        return $user->hasRole('admin') ||
               ($user->hasRole('teacher') && $studentGrade->evaluation->teacher_id === $user->id) ||
               ($user->hasRole('enseignant') && $studentGrade->evaluation->teacher_id === $user->id);
    }

    /**
     * Determine whether the user can delete the student grade.
     */
    public function delete(User $user, StudentGrade $studentGrade): bool
    {
        if ($user->email === 'ronaldoagbohou@gmail.com') {
            return true;
        }
        return $user->hasRole('admin') ||
               ($user->hasRole('teacher') && $studentGrade->evaluation->teacher_id === $user->id) ||
               ($user->hasRole('enseignant') && $studentGrade->evaluation->teacher_id === $user->id);
    }
}
