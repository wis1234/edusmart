<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Subject;
use App\Models\ClassRoom;
use App\Models\School;
use App\Http\Requests\TeacherRequest;
use App\Services\NotificationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TeacherController extends Controller
{
    protected $notificationService;

    /**
     * Create a new controller instance.
     */
    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
        $this->authorizeResource(Teacher::class, 'teacher');
    }

    /**
     * Display a listing of the teachers.
     */
    public function index()
    {
        $user = Auth::user();
        $query = Teacher::with(['user', 'school', 'subjects', 'classRooms']);

        // Si l'utilisateur est un school_admin, filtrer par son école
        if ($user->role === 'school_admin' && $user->school_id) {
            $query->where('school_id', $user->school_id);
        }
        
        // Si l'utilisateur est un enseignant, filtrer par son école (lecture seule)
        if ($user->role === 'enseignant' && $user->school_id) {
            $query->where('school_id', $user->school_id);
        }

        $teachers = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('teachers.index', compact('teachers'));
    }

    /**
     * Show the form for creating a new teacher.
     */
    public function create()
    {
        $user = Auth::user();
        $subjects = Subject::orderBy('name')->get();
        $subjectsBySchool = $subjects->groupBy('school_id');
        
        // Si l'utilisateur est un school_admin, filtrer les salles de classe par son école
        if ($user->role === 'school_admin' && $user->school_id) {
            $classRooms = ClassRoom::where('school_id', $user->school_id)->orderBy('name')->get();
            $schools = School::where('id', $user->school_id)->orderBy('name')->get();
        } else {
            $classRooms = ClassRoom::orderBy('name')->get();
            $schools = School::orderBy('name')->get();
        }

        return view('teachers.create', compact('subjects', 'subjectsBySchool', 'classRooms', 'schools'));
    }

    /**
     * Store a newly created teacher in storage.
     */
    public function store(TeacherRequest $request)
    {
        $user = Auth::user();
        
        DB::beginTransaction();

        $validated = $request->validated();

        // Si l'utilisateur est un school_admin, forcer l'école
        if ($user->role === 'school_admin' && $user->school_id) {
            $validated['schools'] = [$user->school_id];
        }

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            $validated['profile_photo'] = $request->file('profile_photo')->store('teacher-photos', 'public');
        }

        // Create associated user
        $userData = [
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'] ?? null,
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'date_of_birth' => $validated['date_of_birth'],
            'gender' => $validated['gender'],
            'address' => $validated['address'],
            'status' => $validated['status'],
            'password' => bcrypt($validated['password']),
            'profile_photo' => $validated['profile_photo'] ?? null,
            'role' => 'enseignant',
        ];
        $user = \App\Models\User::create($userData);

        // Create teacher record linked to user
        $teacher = Teacher::create([
            'teacher_firstname' => $validated['first_name'],
            'teacher_lastname' => $validated['last_name'] ?? null,
            'teacher_email' => $validated['email'],
            'teacher_phone' => $validated['phone'],
            'date_of_birth' => $validated['date_of_birth'],
            'gender' => $validated['gender'],
            'address' => $validated['address'],
            'grade' => $validated['grade'],
            'speciality' => $validated['speciality'],
            'subject_title' => $validated['subject_title'],
            'status' => $validated['status'],
            'profile_photo' => $validated['profile_photo'] ?? null,
            'school_id' => $validated['schools'][0] ?? null,
            'user_id' => $user->id,
        ]);

        // Sync subjects and classrooms
        $assignments = [];
        if (!empty($validated['class_rooms'])) {
            foreach ($validated['class_rooms'] as $index => $classRoomId) {
                if (!empty($classRoomId) && !empty($validated['subjects'][$index])) {
                    foreach ($validated['subjects'][$index] as $subjectId) {
                        $assignment = [
                            'class_room_id' => $classRoomId,
                            'subject_id' => $subjectId,
                            'year' => $validated['years'][$index] ?? Carbon::now()->year,
                        ];
                        unset($assignment['teacher_id']);
                        $assignments[] = $assignment;
                    }
                }
            }
        }

        if (!empty($assignments)) {
            $teacher->classRoomTeachers()->delete(); // Clear old assignments
            $teacher->classRoomTeachers()->createMany($assignments); // Create new ones
        }

        // Assign teacher role to user
        $user->assignRole('enseignant');

        DB::commit();

        // Notification
        $this->notificationService->sendToRole(
            'admin',
            'New Teacher Created',
            'A new teacher profile has been created in the system.',
            'success',
            route('teachers.show', $teacher)
        );
        return redirect()->route('teachers.index')->with('success', 'Teacher created successfully.');
    }

    /**
     * Display the specified teacher.
     */
    public function show(Teacher $teacher)
    {
        $teacher->load([
            'school',
            'subjects',
            'classRooms',
            'conductedEvaluations.subject',
            'conductedEvaluations.classRoom',
            'conductedEvaluations.evaluationType',
            'conductedEvaluations.studentGrades.student.user',
            'givenGrades.student.user',
            'givenGrades.evaluation.subject',
            'givenGrades.evaluation.evaluationType'
        ]);

        return view('teachers.show', compact('teacher'));
    }

    /**
     * Show the form for editing the specified teacher.
     */
    public function edit(Teacher $teacher)
    {
        $subjects = Subject::orderBy('name')->get();
        $subjectsBySchool = $subjects->groupBy('school_id');
        $classRooms = ClassRoom::orderBy('name')->get();
        $schools = School::orderBy('name')->get();

        $teacher->load(['subjects', 'classRooms', 'school']);

        return view('teachers.edit', compact('teacher', 'subjects', 'subjectsBySchool', 'classRooms', 'schools'));
    }

    /**
     * Update the specified teacher in storage.
     */
    public function update(TeacherRequest $request, Teacher $teacher)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validated();

            // Store old values for comparison
            $oldValues = [
                'first_name' => $teacher->teacher_firstname,
                'last_name' => $teacher->teacher_lastname,
                'email' => $teacher->teacher_email,
                'phone' => $teacher->teacher_phone,
                'date_of_birth' => $teacher->date_of_birth,
                'gender' => $teacher->gender,
                'address' => $teacher->address,
                'grade' => $teacher->grade,
                'speciality' => $teacher->speciality,
                'subject_title' => $teacher->subject_title,
                'status' => $teacher->status,
                'assignments' => $teacher->taughtSubjects->count()
            ];

            // Handle profile photo upload
            if ($request->hasFile('profile_photo')) {
                // Delete old photo if exists
                if ($teacher->profile_photo) {
                    Storage::disk('public')->delete($teacher->profile_photo);
                }
                $validated['profile_photo'] = $request->file('profile_photo')->store('teacher-photos', 'public');
            }

            // Update teacher record
            $teacher->update([
                'teacher_firstname' => $validated['first_name'],
                'teacher_lastname' => $validated['last_name'] ?? null,
                'teacher_email' => $validated['email'],
                'teacher_phone' => $validated['phone'],
                'date_of_birth' => $validated['date_of_birth'],
                'gender' => $validated['gender'],
                'address' => $validated['address'],
                'grade' => $validated['grade'],
                'speciality' => $validated['speciality'],
                'subject_title' => $validated['subject_title'],
                'status' => $validated['status'],
                'profile_photo' => $validated['profile_photo'] ?? $teacher->profile_photo,
            ]);

            // Update associated user
            $teacher->user->update([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'] ?? null,
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'date_of_birth' => $validated['date_of_birth'],
                'gender' => $validated['gender'],
                'address' => $validated['address'],
                'status' => $validated['status'],
                'profile_photo' => $validated['profile_photo'] ?? $teacher->profile_photo,
            ]);

            // Clear existing assignments
            $teacher->subjects()->detach();
            $teacher->classRooms()->detach();

            // Sync subjects and classrooms
            $assignments = [];
            if (!empty($validated['class_rooms'])) {
                foreach ($validated['class_rooms'] as $index => $classRoomId) {
                    if (!empty($classRoomId) && !empty($validated['subjects'][$index])) {
                        foreach ($validated['subjects'][$index] as $subjectId) {
                            $assignment = [
                                'class_room_id' => $classRoomId,
                                'subject_id' => $subjectId,
                                'year' => $validated['years'][$index] ?? Carbon::now()->year,
                            ];
                            unset($assignment['teacher_id']);
                            $assignments[] = $assignment;
                        }
                    }
                }
            }

            if (!empty($assignments)) {
                $teacher->classRoomTeachers()->delete(); // Clear old assignments
                $teacher->classRoomTeachers()->createMany($assignments); // Create new ones
            }

            DB::commit();

            // Notification
            $this->notificationService->sendToRole(
                'admin',
                'Teacher Updated',
                'A teacher profile has been updated in the system.',
                'warning',
                route('teachers.show', $teacher)
            );
            return redirect()
                ->route('teachers.show', $teacher)
                ->with('success', 'Teacher updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Teacher update failed: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', '❌ Failed to update teacher. Please try again.');
        }
    }

    /**
     * Remove the specified teacher from storage.
     */
    public function destroy(Teacher $teacher)
    {
        try {
            DB::beginTransaction();

            // Store teacher info before deletion
            $teacherInfo = [
                'id' => $teacher->id,
                'name' => $teacher->teacher_firstname . ' ' . $teacher->teacher_lastname,
                'email' => $teacher->teacher_email,
                'grade' => $teacher->grade,
                'speciality' => $teacher->speciality,
                'assignments_count' => $teacher->taughtSubjects->count(),
                'subjects' => $teacher->taughtSubjects->pluck('name')->toArray(),
                'class_rooms' => $teacher->teachingClassRooms->pluck('name')->toArray(),
                'created_at' => $teacher->created_at->format('d/m/Y H:i:s'),
                'profile_photo' => $teacher->profile_photo
            ];

            // Check if teacher has associated data
            $hasStudents = $teacher->students()->exists();
            $hasEvaluations = $teacher->conductedEvaluations()->exists();
            $hasGrades = $teacher->givenGrades()->exists();
            $hasNotifications = $teacher->notifications()->exists();
            $hasClassRoomTeachers = $teacher->classRoomTeachers()->exists();

            if ($hasStudents || $hasEvaluations || $hasGrades || $hasNotifications || $hasClassRoomTeachers) {
                $reasons = [];
                if ($hasStudents) $reasons[] = "students";
                if ($hasEvaluations) $reasons[] = "evaluations";
                if ($hasGrades) $reasons[] = "grades";
                if ($hasNotifications) $reasons[] = "notifications";
                if ($hasClassRoomTeachers) $reasons[] = "class room assignments";

                return redirect()
                    ->route('teachers.index')
                    ->with('error', '❌ Cannot delete teacher because they have associated ' . implode(', ', $reasons) . '. Please remove these associations first.');
            }

            // Detach all relationships first
            $teacher->subjects()->detach();
            $teacher->classRooms()->detach();
            $teacher->classRoomTeachers()->delete();

            // Delete profile photo if exists
            if ($teacher->profile_photo && Storage::disk('public')->exists($teacher->profile_photo)) {
                try {
                    Storage::disk('public')->delete($teacher->profile_photo);
                } catch (\Exception $e) {
                    Log::warning('Failed to delete teacher profile photo: ' . $e->getMessage());
                    // Continue with deletion even if photo deletion fails
                }
            }

            // Delete associated user if exists
            if ($teacher->user) {
                try {
                    $teacher->user->delete();
                } catch (\Exception $e) {
                    Log::error('Failed to delete associated user: ' . $e->getMessage());
                    throw new \Exception('Failed to delete associated user account: ' . $e->getMessage());
                }
            }

            // Delete teacher record
            try {
                $teacher->delete();
            } catch (\Exception $e) {
                Log::error('Failed to delete teacher record: ' . $e->getMessage());
                throw new \Exception('Failed to delete teacher record: ' . $e->getMessage());
            }

            DB::commit();

            // Notification
            $this->notificationService->sendToRole(
                'admin',
                'Teacher Deleted',
                'A teacher profile has been deleted from the system.',
                'error'
            );
            return redirect()
                ->route('teachers.index')
                ->with('success', 'Teacher deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            // Log detailed error information
            Log::error('Teacher deletion failed', [
                'teacher_id' => $teacher->id ?? 'unknown',
                'teacher_name' => $teacher->teacher_firstname . ' ' . $teacher->teacher_lastname ?? 'unknown',
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString(),
                'user_id' => Auth::id(),
                'user_name' => Auth::user()->first_name . ' ' . Auth::user()->last_name ?? 'unknown'
            ]);
            
            // Return more specific error message
            $errorMessage = '❌ Failed to delete teacher. ';
            if (str_contains($e->getMessage(), 'foreign key constraint')) {
                $errorMessage .= 'Teacher has associated data that cannot be deleted.';
            } elseif (str_contains($e->getMessage(), 'permission')) {
                $errorMessage .= 'Permission denied.';
            } else {
                $errorMessage .= 'Please try again. If the problem persists, contact support.';
            }
            
            return redirect()
                ->route('teachers.index')
                ->with('error', $errorMessage);
        }
    }
}