<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\ClassRoom;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Student::class, 'student');
    }

    public function index()
    {
        $students = Student::with(['user', 'classRoom', 'school', 'parent'])
            ->orderBy('admission_number')
            ->get();
            $users = User::all(); 
        return view('students.index', compact('students', 'users'));
    }

    public function create()
    {
        $classRooms = ClassRoom::orderBy('name')->get();
        $schools = School::orderBy('name')->get();
        $parents = User::where('role', 'parent')->orderBy('first_name')->get();
        $users = User::orderBy('first_name')->get();

        return view('students.create', compact('classRooms', 'schools', 'parents', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'user_id' => ['nullable', 'exists:users,id'],
            'class_room_id' => ['nullable', 'exists:class_rooms,id'],
            'school_id' => ['required', 'exists:schools,id'],
            'parent_id' => ['nullable', 'exists:users,id'],
            'selected_parent_id' => ['nullable'],
            'admission_number' => ['required', 'string', 'max:255', 'unique:students,admission_number'],
            'roll_number' => ['nullable', 'string', 'max:255', 'sometimes'],
            'admission_date' => ['required', 'date'],
            'date_of_birth' => ['required', 'date'],
            'gender' => ['nullable', Rule::in(['male', 'female', 'other'])],
            'blood_group' => ['nullable', 'string', 'max:10'],
            'address' => ['nullable', 'string'],
            'emergency_contact' => ['nullable', 'string', 'max:255'],
            'medical_conditions' => ['nullable', 'string'],
            'academic_year' => ['required', 'string', 'max:255'],
            'status' => ['required', Rule::in(['active', 'inactive', 'graduated', 'transferred'])],
            'profile_photo' => ['nullable', 'image', 'max:2048'],
        ]);

        // Set user_id to the currently authenticated user if not provided
        if (empty($validated['user_id'])) {
            $validated['user_id'] = Auth::id();
        }

        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile_photos', 'public');
            $validated['profile_photo'] = $path;
        }

        // Fill parent_email and parent_id based on selected_parent_id
        if (!empty($validated['selected_parent_id'])) {
            $parentUserId = $validated['selected_parent_id'];
            $parentUser = \App\Models\User::find($parentUserId);
            if ($parentUser) {
                $validated['parent_email'] = $parentUser->email;
            }
            $parentModel = \App\Models\ParentModel::where('user_id', $parentUserId)->first();
            if ($parentModel) {
                $validated['parent_id'] = $parentModel->id;
            }
        }

        $student = Student::create($validated);

        return redirect()->route('students.show', $student)->with('success', 'Student created successfully.');
    }

public function show(Student $student)
{
    $student->load(['user', 'classRoom', 'school', 'parent', 'grades.evaluation.evaluationType']);
    $users = User::all(); // ou User::where('role', 'parent')->get(); selon ce que tu veux

    return view('students.show', compact('student', 'users'));
}


    public function edit(Student $student)
    {
        $classRooms = ClassRoom::orderBy('name')->get();
        $schools = School::orderBy('name')->get();
        $parents = User::where('role', 'parent')->orderBy('first_name')->get();
        $users = User::orderBy('first_name')->get();

        return view('students.edit', compact('student', 'classRooms', 'schools', 'parents', 'users'));
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'user_id' => ['nullable', 'exists:users,id'],
            'class_room_id' => ['nullable', 'exists:class_rooms,id'],
            'school_id' => ['required', 'exists:schools,id'],
            'selected_parent_id' => ['nullable'],
            'parent_id' => ['nullable', 'exists:users,id'],
            'admission_number' => ['required', 'string', 'max:255', Rule::unique('students', 'admission_number')->ignore($student->id)],
            'roll_number' => ['nullable', 'string', 'max:255'],
            'admission_date' => ['required', 'date'],
            'date_of_birth' => ['required', 'date'],
            'gender' => ['nullable', Rule::in(['male', 'female', 'other'])],
            'blood_group' => ['nullable', 'string', 'max:10'],
            'address' => ['nullable', 'string'],
            'emergency_contact' => ['nullable', 'string', 'max:255'],
            'medical_conditions' => ['nullable', 'string'],
            'academic_year' => ['required', 'string', 'max:255'],
            'status' => ['required', Rule::in(['active', 'inactive', 'graduated', 'transferred'])],
            'profile_photo' => ['nullable', 'image', 'max:2048'],
        ]);

        // Set user_id to the currently authenticated user if not provided
        if (empty($validated['user_id'])) {
            $validated['user_id'] = Auth::id();
        }

        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($student->profile_photo && Storage::disk('public')->exists($student->profile_photo)) {
                Storage::disk('public')->delete($student->profile_photo);
            }
            $path = $request->file('profile_photo')->store('profile_photos', 'public');
            $validated['profile_photo'] = $path;
        }

        $student->update($validated);

        return redirect()->route('students.show', $student)->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        // Delete profile photo if exists
        if ($student->profile_photo && Storage::disk('public')->exists($student->profile_photo)) {
            Storage::disk('public')->delete($student->profile_photo);
        }

        $student->delete();

        return redirect()->route('students.index')->with('success', 'Student deleted successfully.');
    }
}
