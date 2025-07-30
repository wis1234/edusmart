<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\ClassRoom;
use App\Models\Activity;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InertiaDashboardController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index(Request $request)
    {
        $query = Activity::with('user')->latest();

        if (!auth()->user()->hasRole('admin')) {
            $query->where('user_id', auth()->id());
        }

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('user_id') && auth()->user()->hasRole('admin')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $data = [
            'totalStudents' => Student::count(),
            'totalTeachers' => Teacher::count(),
            'totalSchools' => School::count(),
            'totalClasses' => ClassRoom::count(),
            'recentActivities' => $query->paginate(5),
            'activityTypes' => Activity::distinct()->pluck('type'),
        ];

        if (!session()->has('welcome_notification_sent')) {
            $this->notificationService->send(
                auth()->user(),
                'success',
                'Bienvenue sur EduSmart !',
                'Nous sommes ravis de vous accueillir sur notre plateforme.',
                route('dashboard')
            );
            session()->put('welcome_notification_sent', true);
        }

        if ($request->wantsJson()) {
            return response()->json($data);
        }

        return Inertia::render('Dashboard', $data);
    }
}
