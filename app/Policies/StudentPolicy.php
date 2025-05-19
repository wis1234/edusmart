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
    public function viewAny(User $user)
    {
        if ($user->email === 'ronaldoagbohou@gmail.com') {
            return true;
        }
        return $user->hasAnyRole(['admin', 'manager', 'enseignant', 'parent']);
    }

    /**
     * Determine whether the user can view the student.
     */
    public function view(User $user, Student $student)
    {
        if ($user->email === 'ronaldoagbohou@gmail.com') {
            return true;
        }
        if ($user->hasRole('admin') || $user->hasRole('manager')) {
            return true;
        }
        if ($user->hasRole('enseignant')) {
            // Assuming teacher can view students in their class rooms
            return $user->id === $student->classRoom->teacher_id;
        }
        if ($user->hasRole('parent')) {
            return $user->id === $student->parent_id;
        }
        return false;
    }

    /**
     * Determine whether the user can create students.
     */
    public function create(User $user)
    {
        if ($user->email === 'ronaldoagbohou@gmail.com') {
            return true;
        }
        return $user->hasAnyRole(['admin', 'manager', 'enseignant']);
    }

    /**
     * Determine whether the user can update the student.
     */
    public function update(User $user, Student $student)
    {
        if ($user->email === 'ronaldoagbohou@gmail.com') {
            return true;
        }
        return $this->view($user, $student);
    }

    /**
     * Determine whether the user can delete the student.
     */
    public function delete(User $user, Student $student)
    {
        if ($user->email === 'ronaldoagbohou@gmail.com') {
            return true;
        }
        return $user->hasRole('admin');
    }
}
