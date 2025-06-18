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

class DashboardController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index()
    {
        $data = [
            'totalStudents' => Student::count(),
            'totalTeachers' => Teacher::count(),
            'totalSchools' => School::count(),
            'totalClasses' => ClassRoom::count(),
            'recentActivities' => Activity::with('user')
                ->latest()
                ->take(5)
                ->get()
        ];

        // Envoyer une notification de bienvenue si c'est la première connexion
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

        if (request()->wantsJson()) {
            return response()->json($data);
        }

        return view('dashboard', $data);
    }

    public function getContent($type)
    {
        switch ($type) {
            case 'students':
                $data = [
                    'students' => Student::with('school')
                        ->latest()
                        ->take(10)
                        ->get(),
                    'totalStudents' => Student::count(),
                    'recentEnrollments' => Student::latest()->take(5)->get()
                ];
                return view('dashboard.partials.students', $data);

            case 'teachers':
                $data = [
                    'teachers' => Teacher::with('school')
                        ->latest()
                        ->take(10)
                        ->get(),
                    'totalTeachers' => Teacher::count(),
                    'recentHires' => Teacher::latest()->take(5)->get()
                ];
                return view('dashboard.partials.teachers', $data);

            case 'schools':
                $data = [
                    'schools' => School::latest()->take(10)->get(),
                    'totalSchools' => School::count(),
                    'recentSchools' => School::latest()->take(5)->get()
                ];
                return view('dashboard.partials.schools', $data);

            case 'classes':
                $data = [
                    'classes' => ClassRoom::with('school')
                        ->latest()
                        ->take(10)
                        ->get(),
                    'totalClasses' => ClassRoom::count(),
                    'recentClasses' => ClassRoom::latest()->take(5)->get()
                ];
                return view('dashboard.partials.classes', $data);

            default:
                return response()->json(['error' => 'Invalid content type'], 400);
        }
    }
} 