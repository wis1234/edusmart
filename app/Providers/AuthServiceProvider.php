<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Student;
use App\Models\School;
use App\Models\ClassRoom;
use App\Models\Calendar;
use App\Policies\UserPolicy;
use App\Policies\StudentPolicy;
use App\Policies\SchoolPolicy;
use App\Policies\ClassRoomPolicy;
use App\Policies\CalendarPolicy;
use App\Policies\StudentGradePolicy;
use App\Models\StudentGrade;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Student::class => StudentPolicy::class,
        School::class => SchoolPolicy::class,
        ClassRoom::class => ClassRoomPolicy::class,
        Calendar::class => CalendarPolicy::class,
        StudentGrade::class => StudentGradePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
