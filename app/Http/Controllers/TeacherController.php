<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Subject;
use App\Models\ClassRoom;
use App\Http\Requests\TeacherRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TeacherController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->authorizeResource(Teacher::class, 'teacher');
    }

    /**
     * Display a listing of the teachers.
     */
    public function index()
    {
        $teachers = Teacher::with(['subjects', 'classRooms', 'school'])
            ->orderBy('teacher_firstname')
            ->paginate(15);

        return view('teachers.index', compact('teachers'));
    }

    /**
     * Show the form for creating a new teacher.
     */
    public function create()
    {
        $subjects = Subject::orderBy('name')->get();
        $classRooms = ClassRoom::orderBy('name')->get();

        return view('teachers.create', compact('subjects', 'classRooms'));
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
            'school_id' => $validated['school_id'] ?? null,
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

        return redirect()
            ->route('teachers.show', $teacher)
            ->with('success', 'Teacher created successfully.');
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
        
        $teacher->load(['subjects', 'classRooms', 'school']);

        return view('teachers.edit', compact('teacher', 'subjects', 'classRooms'));
    }

    /**
     * Update the specified teacher in storage.
     */
    public function update(TeacherRequest $request, Teacher $teacher)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validated();

            // Handle profile photo upload
            if ($request->hasFile('profile_photo')) {
                // Delete old photo if exists
                $teacher->deleteProfilePhoto();
                
                // Store new photo
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
                'school_id' => $validated['school_id'] ?? null,
            ]);

            if (isset($validated['profile_photo'])) {
                $teacher->profile_photo = $validated['profile_photo'];
                $teacher->save();
            }

            // Sync subjects with years
            $subjectSync = [];
            foreach ($validated['subjects'] as $index => $subjectId) {
                $subjectSync[$subjectId] = ['year' => $validated['years'][$index]];
            }
            $teacher->subjects()->sync($subjectSync);

            // Sync classrooms with subjects and years
            $classRoomSync = [];
            foreach ($validated['class_rooms'] as $index => $classRoomId) {
                $classRoomSync[$classRoomId] = [
                    'subject_id' => $validated['subjects'][$index],
                    'year' => $validated['years'][$index]
                ];
            }
            $teacher->classRooms()->sync($classRoomSync);

            DB::commit();

            return redirect()
                ->route('teachers.show', $teacher)
                ->with('success', 'Teacher updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            if (isset($validated['profile_photo'])) {
                Storage::disk('public')->delete($validated['profile_photo']);
            }

            Log::error('Failed to update teacher', [
                'id' => $teacher->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->withInput()
                ->with('error', 'An error occurred while updating the teacher.');
        }
    }

    /**
     * Remove the specified teacher from storage.
     */
    public function destroy(Teacher $teacher)
    {
        try {
            DB::beginTransaction();

            // Delete profile photo if exists
            $teacher->deleteProfilePhoto();

            // Delete teacher and related records
            $teacher->delete();

            DB::commit();

            return redirect()
                ->route('teachers.index')
                ->with('success', 'Teacher deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to delete teacher', [
                'id' => $teacher->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'An error occurred while deleting the teacher.');
        }
    }
}
