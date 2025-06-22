<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\School;
use App\Http\Requests\ClassRoomRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClassRoomController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(ClassRoom::class, 'class_room');
    }

    /**
     * Display a listing of the classroom resources.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = ClassRoom::with(['school', 'createdBy:id,first_name,last_name', 'updatedBy:id,first_name,last_name']);

        // Si l'utilisateur est un school_admin, filtrer par son école
        if ($user->role === 'school_admin' && $user->school_id) {
            $query->where('school_id', $user->school_id);
        }
        
        // Si l'utilisateur est un enseignant, filtrer par ses classes assignées
        if ($user->role === 'enseignant') {
            $teacherId = $user->teacherProfile ? $user->teacherProfile->id : null;
            if ($teacherId) {
                $query->whereHas('classRoomTeachers', function($q) use ($teacherId) {
                    $q->where('teacher_id', $teacherId);
                });
            } else {
                // Aucun profil enseignant, retourner une collection vide
                $query->whereRaw('1 = 0');
            }
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('grade_level', 'like', "%{$search}%")
                  ->orWhere('section', 'like', "%{$search}%")
                  ->orWhere('academic_year', 'like', "%{$search}%")
                  ->orWhereHas('school', function ($schoolQuery) use ($search) {
                      $schoolQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by school
        if ($request->filled('school')) {
            $query->where('school_id', $request->school);
        }

        // Filter by grade level
        if ($request->filled('grade_level')) {
            $query->where('grade_level', $request->grade_level);
        }

        // Filter by academic year
        if ($request->filled('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }

        // Filter by capacity range
        if ($request->filled('capacity_min')) {
            $query->where('capacity', '>=', $request->capacity_min);
        }
        if ($request->filled('capacity_max')) {
            $query->where('capacity', '<=', $request->capacity_max);
        }

        $classRooms = $query->orderBy('name')->paginate(10);
        
        // Filtrer les écoles par école pour les school_admin
        if ($user->role === 'school_admin' && $user->school_id) {
            $schools = School::where('id', $user->school_id)->orderBy('name')->get();
        } else {
            $schools = School::orderBy('name')->get();
        }
        
        return view('class_rooms.index', compact('classRooms', 'schools'));
    }

    /**
     * Show the form for creating a new classroom resource.
     */
    public function create()
    {
        $user = Auth::user();
        
        // Si l'utilisateur est un school_admin, filtrer les écoles par son école
        if ($user->role === 'school_admin' && $user->school_id) {
            $schools = School::where('id', $user->school_id)->orderBy('name')->get();
        } else {
            $schools = School::orderBy('name')->get();
        }
        
        return view('class_rooms.create', compact('schools'));
    }

    /**
     * Store a newly created classroom resource in storage.
     */
    public function store(ClassRoomRequest $request)
    {
        $validated = $request->validated();

        // Vérifier unicité de la combinaison grade_level + section + academic_year + school_id
        $exists = \App\Models\ClassRoom::where('school_id', $validated['school_id'])
            ->where('academic_year', $validated['academic_year'])
            ->where('grade_level', $validated['grade_level'])
            ->where('section', $validated['section'])
            ->exists();
        if ($exists) {
            return back()->withErrors(['section' => 'This grade level and section already exists for this school and academic year.'])->withInput();
        }

        $validated['created_by'] = auth()->id();
        $validated['updated_by'] = auth()->id();
        $classRoom = ClassRoom::create($validated);
        // Notification
        app(\App\Services\NotificationService::class)->sendToRole(
            'admin',
            'New Class Created',
            'A new class has been created in the system.',
            'success',
            route('class_rooms.show', $classRoom)
        );
        return redirect()
            ->route('class_rooms.show', $classRoom)
            ->with('success', 'Classroom created successfully.');
    }

    /**
     * Display the specified classroom resource.
     */
    public function show(ClassRoom $classRoom)
    {
        $classRoom->load([
            'school',
            'teachers',
            'students',
            'evaluations',
            'subjects',
            'classRoomTeachers',
            'createdBy:id,first_name,last_name',
            'updatedBy:id,first_name,last_name',
        ]);
        
        return view('class_rooms.show', compact('classRoom'));
    }

    /**
     * Show the form for editing the specified classroom resource.
     */
    public function edit(ClassRoom $classRoom)
    {
        $user = Auth::user();
        
        // Si l'utilisateur est un school_admin, filtrer les écoles par son école
        if ($user->role === 'school_admin' && $user->school_id) {
            $schools = School::where('id', $user->school_id)->orderBy('name')->get();
        } else {
            $schools = School::orderBy('name')->get();
        }
        
        return view('class_rooms.edit', compact('classRoom', 'schools'));
    }

    /**
     * Update the specified classroom resource in storage.
     */
    public function update(ClassRoomRequest $request, ClassRoom $classRoom)
    {
        $validated = $request->validated();
        $validated['updated_by'] = auth()->id();

        $classRoom->update($validated);
        // Notification
        app(\App\Services\NotificationService::class)->sendToRole(
            'admin',
            'Class Updated',
            'A class has been updated in the system.',
            'warning',
            route('class_rooms.show', $classRoom)
        );
        return redirect()
            ->route('class_rooms.show', $classRoom)
            ->with('success', 'Classroom updated successfully.');
    }

    /**
     * Remove the specified classroom resource from storage.
     */
    public function destroy(ClassRoom $classRoom)
    {
        if ($classRoom->students()->exists()) {
            return back()->with('error', 'Cannot delete classroom because it has associated students.');
        }

        $classRoom->delete();
        // Notification
        app(\App\Services\NotificationService::class)->sendToRole(
            'admin',
            'Class Deleted',
            'A class has been deleted from the system.',
            'error'
        );
        return redirect()
            ->route('class_rooms.index')
            ->with('success', 'Classroom deleted successfully.');
    }

    /**
     * API: Get classrooms for a given subject, filtered for the authenticated teacher
     */
    public function apiBySubject(Request $request)
    {
        $user = Auth::user();
        $subjectId = $request->query('subject_id');
        if (!$subjectId) {
            return response()->json([], 200);
        }

        // For teachers: only classrooms assigned via class_room_teacher for this subject
        if ($user->hasRole('enseignant') && $user->teacherProfile) {
            $teacherId = $user->teacherProfile->id;
            $classRooms = \App\Models\ClassRoom::whereHas('classRoomTeachers', function($q) use ($teacherId, $subjectId) {
                $q->where('teacher_id', $teacherId)
                  ->where('subject_id', $subjectId);
            })->orderBy('name')->get(['id', 'name']);
        } else {
            // Admins: all classrooms for the subject
            $classRooms = \App\Models\ClassRoom::whereHas('subjects', function($q) use ($subjectId) {
                $q->where('subjects.id', $subjectId);
            })->orderBy('name')->get(['id', 'name']);
        }
        return response()->json($classRooms);
    }
}
