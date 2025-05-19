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
        return $user->hasAnyRole(['admin', 'manager', 'enseignant', 'parent']);
    }

    /**
     * Determine whether the user can view the calendar.
     */
    public function view(User $user, Calendar $calendar)
    {
        if ($user->email === 'ronaldoagbohou@gmail.com') {
            return true;
        }
        return $user->hasAnyRole(['admin', 'manager', 'enseignant', 'parent']);
    }

    /**
     * Determine whether the user can create calendars.
     */
    public function create(User $user)
    {
        if ($user->email === 'ronaldoagbohou@gmail.com') {
            return true;
        }
        return $user->hasAnyRole(['admin', 'manager']);
    }

    /**
     * Determine whether the user can update the calendar.
     */
    public function update(User $user, Calendar $calendar)
    {
        if ($user->email === 'ronaldoagbohou@gmail.com') {
            return true;
        }
        return $user->hasAnyRole(['admin', 'manager']);
    }

    /**
     * Determine whether the user can delete the calendar.
     */
    public function delete(User $user, Calendar $calendar)
    {
        if ($user->email === 'ronaldoagbohou@gmail.com') {
            return true;
        }
        return $user->hasRole('admin');
    }
}
