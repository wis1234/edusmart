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

    public function index(Request $request)
    {
        $query = Activity::with('user')->latest();

        // Restriction d'accès : seul l'admin voit tout, les autres voient leurs propres activités
        if (!auth()->user()->hasRole('admin')) {
            $query->where('user_id', auth()->id());
        }

        // Filtrer par type si spécifié
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        // Filtrer par utilisateur si spécifié (admin uniquement)
        if ($request->has('user_id') && auth()->user()->hasRole('admin')) {
            $query->where('user_id', $request->user_id);
        }

        // Filtrer par date si spécifié
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

        if ($request->wantsJson()) {
            return response()->json($data);
        }

        return view('dashboard', $data);
    }

    public function deleteActivity($id)
    {
        try {
            $activity = Activity::findOrFail($id);
            $activity->delete();
            
            // Log the deletion
            Activity::log('delete', 'Deleted activity log entry');
            
            return response()->json(['message' => 'Activity deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete activity'], 500);
        }
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