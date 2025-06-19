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
        $query = Teacher::with(['subjects', 'teachingClassRooms.school'])
            ->orderBy('teacher_firstname');

        // Recherche
        if (request()->filled('search')) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('teacher_firstname', 'like', "%$search%")
                  ->orWhere('teacher_lastname', 'like', "%$search%")
                  ->orWhere('teacher_email', 'like', "%$search%")
                  ->orWhere('teacher_phone', 'like', "%$search%")
                  ->orWhere('address', 'like', "%$search%")
                  ->orWhere('grade', 'like', "%$search%")
                  ->orWhere('speciality', 'like', "%$search%")
                  ;
            });
        }

        // Filtre statut
        if (request()->filled('status')) {
            $query->where('status', request('status'));
        }

        // Filtre école
        if (request()->filled('school')) {
            $query->whereHas('teachingClassRooms.school', function($q) {
                $q->where('id', request('school'));
            });
        }

        // Filtre matière
        if (request()->filled('subject')) {
            $query->whereHas('subjects', function($q) {
                $q->where('subjects.id', request('subject'));
            });
        }

        $teachers = $query->paginate(12)->appends(request()->except('page'));

        // Pour les filtres dropdown
        $schools = \App\Models\School::orderBy('name')->get();
        $subjects = \App\Models\Subject::orderBy('name')->get();

        return view('teachers.index', compact('teachers', 'schools', 'subjects'));
    }

    /**
     * Show the form for creating a new teacher.
     */
    public function create()
    {
        $subjects = Subject::orderBy('name')->get();
        $classRooms = ClassRoom::orderBy('name')->get();
        $schools = School::orderBy('name')->get();

        return view('teachers.create', compact('subjects', 'classRooms', 'schools'));
    }

    /**
     * Store a newly created teacher in storage.
     */
    public function store(TeacherRequest $request)
    {
        DB::beginTransaction();

        $validated = $request->validated();

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

        // Attach subjects with years
        foreach ($validated['subjects'] as $index => $subjectId) {
            $teacher->subjects()->attach($subjectId, [
                'year' => $validated['years'][$index]
            ]);
        }

        // Attach classrooms with subjects and years
        foreach ($validated['class_rooms'] as $index => $classRoomId) {
            $teacher->classRooms()->attach($classRoomId, [
                'subject_id' => $validated['subjects'][$index],
                'year' => $validated['years'][$index]
            ]);
        }

        DB::commit();

        // Notification détaillée de création
        $currentUser = Auth::user();
        $currentTime = Carbon::now()->format('d/m/Y H:i:s');
        $assignmentsCount = count($validated['subjects']);
        
        $notificationMessage = "🎉 NEW TEACHER CREATED\n\n" .
            "Teacher Profile Created Successfully\n\n" .
            "Personal Information:\n" .
            "   Full Name: {$teacher->teacher_firstname} {$teacher->teacher_lastname}\n" .
            "   Email: {$teacher->teacher_email}\n" .
            "   Phone: {$teacher->teacher_phone}\n" .
            "   Grade: {$teacher->grade}\n" .
            "   Speciality: {$teacher->speciality}\n" .
            "   Status: " . ucfirst($teacher->status) . "\n\n" .
            "Teaching Assignments: {$assignmentsCount} assignment(s)\n" .
            "   Subjects: " . implode(', ', array_map(function($subjectId) {
                $subject = Subject::find($subjectId);
                return $subject ? $subject->name : 'Unknown';
            }, $validated['subjects'])) . "\n" .
            "   Class Rooms: " . implode(', ', array_map(function($classRoomId) {
                $classRoom = ClassRoom::find($classRoomId);
                return $classRoom ? $classRoom->name : 'Unknown';
            }, $validated['class_rooms'])) . "\n\n" .
            "Created By: {$currentUser->first_name} {$currentUser->last_name}\n" .
            "Date & Time: {$currentTime}\n" .
            "Action: Teacher account created with full profile and teaching assignments";

        $this->notificationService->sendToRole(
            'admin',
            'success',
            'New Teacher Added to System',
            $notificationMessage,
            route('teachers.show', $teacher),
            [
                'teacher_id' => $teacher->id,
                'teacher_name' => $teacher->teacher_firstname . ' ' . $teacher->teacher_lastname,
                'created_by' => $currentUser->id,
                'created_at' => $currentTime,
                'assignments_count' => $assignmentsCount
            ]
        );

        return redirect()
            ->route('teachers.show', $teacher)
            ->with('success', '✅ Teacher created successfully with ' . $assignmentsCount . ' teaching assignment(s)!');
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
        $classRooms = ClassRoom::orderBy('name')->get();
        $schools = School::orderBy('name')->get();

        $teacher->load(['subjects', 'classRooms', 'school']);

        return view('teachers.edit', compact('teacher', 'subjects', 'classRooms', 'schools'));
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

            // Attach new assignments
            foreach ($validated['subjects'] as $index => $subjectId) {
                $teacher->subjects()->attach($subjectId, [
                    'year' => $validated['years'][$index]
                ]);
            }

            foreach ($validated['class_rooms'] as $index => $classRoomId) {
                $teacher->classRooms()->attach($classRoomId, [
                    'subject_id' => $validated['subjects'][$index],
                    'year' => $validated['years'][$index]
                ]);
            }

            DB::commit();

            // Notification détaillée de modification
            $currentUser = Auth::user();
            $currentTime = Carbon::now()->format('d/m/Y H:i:s');
            $newAssignmentsCount = count($validated['subjects']);
            
            // Détecter les changements
            $changes = [];
            if ($oldValues['first_name'] !== $validated['first_name']) {
                $changes[] = "First Name: '{$oldValues['first_name']}' → '{$validated['first_name']}'";
            }
            if ($oldValues['last_name'] !== ($validated['last_name'] ?? null)) {
                $changes[] = "Last Name: '{$oldValues['last_name']}' → '{$validated['last_name']}'";
            }
            if ($oldValues['email'] !== $validated['email']) {
                $changes[] = "Email: '{$oldValues['email']}' → '{$validated['email']}'";
            }
            if ($oldValues['phone'] !== $validated['phone']) {
                $changes[] = "Phone: '{$oldValues['phone']}' → '{$validated['phone']}'";
            }
            if ($oldValues['grade'] !== $validated['grade']) {
                $changes[] = "Grade: '{$oldValues['grade']}' → '{$validated['grade']}'";
            }
            if ($oldValues['speciality'] !== $validated['speciality']) {
                $changes[] = "Speciality: '{$oldValues['speciality']}' → '{$validated['speciality']}'";
            }
            if ($oldValues['status'] !== $validated['status']) {
                $changes[] = "Status: '{$oldValues['status']}' → '{$validated['status']}'";
            }
            if ($oldValues['assignments'] !== $newAssignmentsCount) {
                $changes[] = "Teaching Assignments: {$oldValues['assignments']} → {$newAssignmentsCount}";
            }
            if ($request->hasFile('profile_photo')) {
                $changes[] = "Profile Photo: Updated";
            }

            $changesText = empty($changes) ? "No significant changes detected" : implode("\n   • ", $changes);
            
            $notificationMessage = "✏️ TEACHER PROFILE UPDATED\n\n" .
                "Profile Modification Summary\n\n" .
                "Teacher Information:\n" .
                "   Teacher ID: #{$teacher->id}\n" .
                "   Full Name: {$teacher->teacher_firstname} {$teacher->teacher_lastname}\n\n" .
                "Changes Made:\n   • {$changesText}\n\n" .
                "Current Teaching Assignments: {$newAssignmentsCount} assignment(s)\n" .
                "   Subjects: " . implode(', ', array_map(function($subjectId) {
                    $subject = Subject::find($subjectId);
                    return $subject ? $subject->name : 'Unknown';
                }, $validated['subjects'])) . "\n" .
                "   Class Rooms: " . implode(', ', array_map(function($classRoomId) {
                    $classRoom = ClassRoom::find($classRoomId);
                    return $classRoom ? $classRoom->name : 'Unknown';
                }, $validated['class_rooms'])) . "\n\n" .
                "Updated By: {$currentUser->first_name} {$currentUser->last_name}\n" .
                "Date & Time: {$currentTime}\n" .
                "Action: Teacher profile and assignments updated successfully";

            $this->notificationService->sendToRole(
                'admin',
                'warning',
                'Teacher Profile Modified',
                $notificationMessage,
                route('teachers.show', $teacher),
                [
                    'teacher_id' => $teacher->id,
                    'teacher_name' => $teacher->teacher_firstname . ' ' . $teacher->teacher_lastname,
                    'updated_by' => $currentUser->id,
                    'updated_at' => $currentTime,
                    'changes_count' => count($changes),
                    'changes' => $changes,
                    'assignments_count' => $newAssignmentsCount
                ]
            );

            return redirect()
                ->route('teachers.show', $teacher)
                ->with('success', '✅ Teacher updated successfully with ' . count($changes) . ' change(s) and ' . $newAssignmentsCount . ' teaching assignment(s)!');

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

            // Notification détaillée de suppression
            $currentUser = Auth::user();
            $currentTime = Carbon::now()->format('d/m/Y H:i:s');
            
            $notificationMessage = "🗑️ TEACHER ACCOUNT REMOVED\n\n" .
                "Account Deletion Summary\n\n" .
                "Teacher Information:\n" .
                "   Teacher ID: #{$teacherInfo['id']}\n" .
                "   Full Name: {$teacherInfo['name']}\n" .
                "   Email: {$teacherInfo['email']}\n" .
                "   Grade: {$teacherInfo['grade']}\n" .
                "   Speciality: {$teacherInfo['speciality']}\n\n" .
                "Teaching History:\n" .
                "   Total Assignments: {$teacherInfo['assignments_count']}\n" .
                "   Subjects Taught: " . implode(', ', $teacherInfo['subjects']) . "\n" .
                "   Class Rooms: " . implode(', ', $teacherInfo['class_rooms']) . "\n" .
                "   Account Created: {$teacherInfo['created_at']}\n\n" .
                "Deletion Details:\n" .
                "   Deleted By: {$currentUser->first_name} {$currentUser->last_name}\n" .
                "   Date & Time: {$currentTime}\n" .
                "   Action: Teacher account and all associated data permanently removed\n" .
                "   Profile Photo: " . ($teacherInfo['profile_photo'] ? 'Deleted' : 'None existed') . "\n\n" .
                "⚠️ IMPORTANT: This action is irreversible. All teacher data has been permanently deleted.";

            $this->notificationService->sendToRole(
                'admin',
                'error',
                'Teacher Account Deleted',
                $notificationMessage,
                null,
                [
                    'teacher_id' => $teacherInfo['id'],
                    'teacher_name' => $teacherInfo['name'],
                    'deleted_by' => $currentUser->id,
                    'deleted_at' => $currentTime,
                    'assignments_count' => $teacherInfo['assignments_count'],
                    'subjects_taught' => $teacherInfo['subjects'],
                    'class_rooms' => $teacherInfo['class_rooms'],
                    'account_created_at' => $teacherInfo['created_at']
                ]
            );

            return redirect()
                ->route('teachers.index')
                ->with('success', '🗑️ Teacher "' . $teacherInfo['name'] . '" has been permanently deleted from the system.');

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