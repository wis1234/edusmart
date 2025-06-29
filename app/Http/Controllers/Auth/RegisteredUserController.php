<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Teacher;
use App\Models\ParentModel;
use App\Models\Student;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\DB;

class RegisteredUserController extends Controller
{
    /**
     * Show the registration view.
     */
    public function create()
    {
        $schools = \App\Models\School::orderBy('name')->get(['id', 'name']);
        $classrooms = \App\Models\ClassRoom::where('is_active', true)
            ->with('school:id,name')
            ->orderBy('name')
            ->get(['id', 'name', 'school_id', 'grade_level', 'section', 'academic_year']);
        
        // Get subjects for teacher selection
        $subjects = \App\Models\Subject::orderBy('name')->get(['id', 'name']);
        
        // Get parents for selection (with partial names for privacy)
        $parents = \App\Models\User::where('role', 'parent')
            ->orderBy('first_name')
            ->get(['id', 'first_name', 'last_name', 'email']);
        
        return view('auth.register', compact('schools', 'classrooms', 'subjects', 'parents'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $baseRules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:student,parent,teacher'],
            'school_id' => ['required', 'exists:schools,id'],
        ];

        $studentRules = [
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'admission_date' => 'required|date',
            'academic_year' => 'required|string',
            'class_room_id' => 'required|exists:class_rooms,id',
            'selected_parent_id' => 'nullable|exists:users,id',
            'blood_group' => 'nullable|string|max:20',
            'emergency_contact' => 'nullable|string|max:50',
            'student_address' => 'nullable|string|max:255',
            'medical_conditions' => 'nullable|string|max:255',
            'profile_photo' => 'nullable|image|max:2048',
        ];
        $teacherRules = [
            'speciality' => 'required|string|max:255',
            'teacher_phone' => 'required|string|max:20',
            'teacher_date_of_birth' => 'required|date',
            'teacher_gender' => 'required|in:male,female,other',
            'address' => 'required|string|max:255',
            'grade' => 'required|string|max:100',
            'teacher_subject' => 'required|exists:subjects,id',
            'teacher_classroom' => 'required|exists:class_rooms,id',
            'teacher_academic_year' => 'required|string|max:20',
            'teacher_profile_photo' => 'nullable|image|max:2048',
        ];
        $parentRules = [
            'profession' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'parent_date_of_birth' => 'nullable|date',
            'parent_address' => 'nullable|string|max:255',
            'parent_profile_photo' => 'nullable|image|max:2048',
        ];

        $rules = $baseRules;
        if ($request->role === 'student') {
            $rules = array_merge($rules, $studentRules);
        } elseif ($request->role === 'teacher') {
            $rules = array_merge($rules, $teacherRules);
        } elseif ($request->role === 'parent') {
            $rules = array_merge($rules, $parentRules);
        }

        $validated = $request->validate($rules);

        // Gérer l'upload de la photo de profil (si présente)
        $profilePhotoPath = null;
        if ($request->role === 'student' && $request->hasFile('profile_photo')) {
            $profilePhotoPath = $request->file('profile_photo')->store('profile_photos', 'public');
        } elseif ($request->role === 'teacher' && $request->hasFile('teacher_profile_photo')) {
            $profilePhotoPath = $request->file('teacher_profile_photo')->store('profile_photos', 'public');
        } elseif ($request->role === 'parent' && $request->hasFile('parent_profile_photo')) {
            $profilePhotoPath = $request->file('parent_profile_photo')->store('profile_photos', 'public');
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'school_id' => $request->school_id,
            'validated' => false,
            'status' => 'inactive',
            'profile_photo' => $profilePhotoPath,
        ]);
        $user->assignRole($request->role);

        if ($request->role === 'teacher') {
            $teacher = Teacher::create([
                'user_id' => $user->id,
                'teacher_firstname' => $request->first_name,
                'teacher_lastname' => $request->last_name,
                'teacher_email' => $request->email,
                'speciality' => $request->speciality,
                'subject_title' => $request->speciality,
                'teacher_phone' => $request->teacher_phone,
                'school_id' => $request->school_id,
                'date_of_birth' => $request->teacher_date_of_birth,
                'gender' => $request->teacher_gender,
                'address' => $request->address,
                'grade' => $request->grade,
                'profile_photo' => $profilePhotoPath,
            ]);
            
            // Verify teacher was created successfully
            if (!$teacher) {
                throw new \Exception('Failed to create teacher record');
            }
            
            // Create teacher-classroom-subject assignment in class_room_teacher table
            if ($request->teacher_subject && $request->teacher_classroom) {
                // Extract the first year from academic year string (e.g., "2024-2025" -> 2024)
                $year = explode('-', $request->teacher_academic_year)[0];
                
                DB::table('class_room_teacher')->insert([
                    'teacher_id' => $teacher->id,
                    'class_room_id' => $request->teacher_classroom,
                    'subject_id' => $request->teacher_subject,
                    'year' => $year,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } elseif ($request->role === 'parent') {
            ParentModel::create([
                'user_id' => $user->id,
                'profession' => $request->profession,
                'phone' => $request->phone,
                'date_of_birth' => $request->parent_date_of_birth,
                'address' => $request->parent_address,
                'profile_photo' => $profilePhotoPath,
            ]);
        } elseif ($request->role === 'student') {
            // Auto-generate unique admission_number
            $year = date('Y');
            do {
                $admission_number = 'S' . $year . mt_rand(10000, 99999);
            } while (Student::where('admission_number', $admission_number)->exists());
            
            // Handle parent selection
            $parentEmail = null;
            $parentId = null;
            $selectedParentId = null;
            if (!empty($request->selected_parent_id)) {
                $parentUser = \App\Models\User::find($request->selected_parent_id);
                if ($parentUser) {
                    $parentEmail = $parentUser->email;
                    $selectedParentId = $parentUser->id; // Set selected_parent_id to user ID
                }
                $parentModel = \App\Models\ParentModel::where('user_id', $request->selected_parent_id)->first();
                if ($parentModel) {
                    $parentId = $parentModel->id;
                }
            }
            
            // Create student record with admission_number included
            Student::create([
                'user_id' => $user->id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'school_id' => $request->school_id,
                'class_room_id' => $request->class_room_id,
                'admission_number' => $admission_number,
                'admission_date' => $request->admission_date,
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'academic_year' => $request->academic_year,
                'status' => 'inactive',
                'blood_group' => $request->blood_group,
                'emergency_contact' => $request->emergency_contact,
                'address' => $request->student_address,
                'medical_conditions' => $request->medical_conditions,
                'profile_photo' => $profilePhotoPath,
                'parent_email' => $parentEmail,
                'parent_id' => $parentId,
                'selected_parent_id' => $selectedParentId,
            ]);
        }

        event(new Registered($user));

        return redirect()->route('registration.success');
    }
}
