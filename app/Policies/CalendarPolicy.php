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
        return $user->hasAnyRole(['admin', 'manager', 'enseignant']);
    }

    /**
     * Determine whether the user can view the calendar.
     */
    public function view(User $user, Calendar $calendar)
    {
        return $user->hasAnyRole(['admin', 'manager', 'enseignant']);
    }

    /**
     * Determine whether the user can create calendars.
     */
    public function create(User $user)
    {
        return $user->hasAnyRole(['admin', 'manager']);
    }

    /**
     * Determine whether the user can update the calendar.
     */
    public function update(User $user, Calendar $calendar)
    {
        return $user->hasAnyRole(['admin', 'manager']);
    }

    /**
     * Determine whether the user can delete the calendar.
     */
    public function delete(User $user, Calendar $calendar)
    {
        return $user->hasRole('admin');
    }
}
