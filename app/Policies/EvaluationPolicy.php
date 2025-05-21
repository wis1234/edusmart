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
        return $user->hasRole('admin') || $user->hasRole('teacher') || $user->hasRole('enseignant');
    }

    /**
     * Determine whether the user can view the evaluation.
     */
    public function view(User $user, Evaluation $evaluation): bool
    {
        return $user->hasRole('admin') || 
               ($user->hasRole('teacher') && $evaluation->teacher_id === $user->id) ||
               ($user->hasRole('enseignant') && $evaluation->teacher_id === $user->id);
    }

    /**
     * Determine whether the user can create evaluations.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('teacher') || $user->hasRole('enseignant');
    }

    /**
     * Determine whether the user can update the evaluation.
     */
    public function update(User $user, Evaluation $evaluation): bool
    {
        return $user->hasRole('admin') || 
               ($user->hasRole('teacher') && $evaluation->teacher_id === $user->id) ||
               ($user->hasRole('enseignant') && $evaluation->teacher_id === $user->id);
    }

    /**
     * Determine whether the user can delete the evaluation.
     */
    public function delete(User $user, Evaluation $evaluation): bool
    {
        return $user->hasRole('admin') || 
               ($user->hasRole('teacher') && $evaluation->teacher_id === $user->id) ||
               ($user->hasRole('enseignant') && $evaluation->teacher_id === $user->id);
    }
}
