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
use Illuminate\Support\Facades\DB;
use App\Services\NotificationService;

class StudentController extends Controller
{
    protected $notificationService;
    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
        $this->authorizeResource(Student::class, 'student');
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Student::with(['user', 'classRoom', 'school', 'parent']);

        // Les admins et school admins peuvent voir tous les étudiants (actifs et inactifs)
        // Les autres rôles ne voient que les étudiants actifs
        if (!($user->hasRole('admin') || $user->hasRole('manager') || $user->role === 'school_admin')) {
            $query->where('status', 'active');
        }

        // Si l'utilisateur est un school_admin, filtrer par son école
        if ($user->role === 'school_admin' && $user->school_id) {
            $query->where('school_id', $user->school_id);
        }
        
        // Si l'utilisateur est un enseignant, filtrer par ses classes via class_room_teacher
        if ($user->role === 'enseignant') {
            $teacher = $user->teacherProfile;
            if ($teacher) {
                $classRoomIds = DB::table('class_room_teacher')
                    ->where('teacher_id', $teacher->id)
                    ->pluck('class_room_id')
                    ->unique()
                    ->filter()
                    ->all();
                $query->whereIn('class_room_id', $classRoomIds);
            } else {
                $query->whereRaw('1=0');
            }
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%$search%")
                  ->orWhere('last_name', 'like', "%$search%")
                  ->orWhere('admission_number', 'like', "%$search%")
                  ->orWhere('roll_number', 'like', "%$search%")
                  ->orWhere('gender', 'like', "%$search%")
                  ->orWhere('status', 'like', "%$search%")
                  ->orWhereHas('classRoom', function($q2) use ($search) {
                      $q2->where('name', 'like', "%$search%")
                         ->orWhere('grade_level', 'like', "%$search%")
                         ;
                  })
                  ->orWhereHas('parent', function($q2) use ($search) {
                      $q2->where('first_name', 'like', "%$search%")
                         ->orWhere('last_name', 'like', "%$search%")
                         ->orWhere('email', 'like', "%$search%")
                         ;
                  });
            });
        }

        // Filter by class room
        if ($request->filled('class_room')) {
            $query->where('class_room_id', $request->input('class_room'));
        }

        // Filter by parent
        if ($request->filled('parent')) {
            $query->where('selected_parent_id', $request->input('parent'));
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $students = $query->orderBy('admission_number')->paginate(10)->appends($request->except('page'));
        
        // Filtrer les salles de classe par école pour les school_admin
        if ($user->role === 'school_admin' && $user->school_id) {
            $classRooms = ClassRoom::where('school_id', $user->school_id)->orderBy('name')->get();
        } else {
            $classRooms = ClassRoom::orderBy('name')->get();
        }
        
        $users = User::orderBy('first_name')->get();
        return view('students.index', compact('students', 'classRooms', 'users'));
    }

    public function create()
    {
        $user = Auth::user();
        // Si l'utilisateur est un school_admin, filtrer par son école
        if ($user->role === 'school_admin' && $user->school_id) {
            $classRooms = ClassRoom::where('school_id', $user->school_id)->orderBy('name')->get();
            $schools = School::where('id', $user->school_id)->orderBy('name')->get();
            $parents = User::where('role', 'parent')->where('school_id', $user->school_id)->orderBy('first_name')->get();
        } else {
            $classRooms = ClassRoom::orderBy('name')->get();
            $schools = School::orderBy('name')->get();
            $parents = User::where('role', 'parent')->orderBy('first_name')->get();
        }
        $users = User::orderBy('first_name')->get();
        return view('students.create', compact('classRooms', 'schools', 'parents', 'users'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'user_id' => ['nullable', 'exists:users,id'],
            'class_room_id' => ['nullable', 'exists:class_rooms,id'],
            'school_id' => ['required', 'exists:schools,id'],
            'parent_id' => ['nullable', 'exists:users,id'],
            'selected_parent_id' => ['nullable'],
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

        // Si l'utilisateur est un school_admin, forcer l'école
        if ($user->role === 'school_admin' && $user->school_id) {
            $validated['school_id'] = $user->school_id;
        }

        // Création du compte utilisateur pour l'étudiant
        $studentUser = \App\Models\User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => 'student',
            'status' => $validated['status'],
            'school_id' => $validated['school_id'],
            'validated' => true, // Auto-validate when created by admin
        ]);
        $studentUser->assignRole('student');
        $validated['user_id'] = $studentUser->id;

        // Auto-generate unique admission_number
        $year = date('Y');
        do {
            $admission_number = 'S' . $year . mt_rand(10000, 99999);
        } while (Student::where('admission_number', $admission_number)->exists());
        $validated['admission_number'] = $admission_number;

        // Auto-generate roll_number: next available for class_room_id + academic_year
        $roll_number = null;
        if (!empty($validated['class_room_id']) && !empty($validated['academic_year'])) {
            $maxRoll = Student::where('class_room_id', $validated['class_room_id'])
                ->where('academic_year', $validated['academic_year'])
                ->max('roll_number');
            $roll_number = $maxRoll ? ((int)$maxRoll + 1) : 1;
        }
        $validated['roll_number'] = $roll_number;

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
            } else {
                $validated['parent_id'] = null;
            }
        }

        $student = Student::create($validated);
        // Notification to all school_admins of the student's school
        $schoolAdmins = \App\Models\User::where('role', 'school_admin')->where('school_id', $validated['school_id'])->get();
        $this->notificationService->sendToMany(
            $schoolAdmins,
            'success',
            'New Student Created',
            'A new student profile has been created in the system.',
            route('students.show', $student)
        );
        return redirect()->route('students.index')->with('success', 'Student created successfully.');
    }

    public function show(Student $student)
    {
        $user = Auth::user();
        if ($user->hasRole('student') && $user->id !== $student->user_id) {
            abort(403, "You are not allowed to view another student's profile.");
        }
        // Vérifier si l'utilisateur peut voir le profil selon la logique de verrouillage
        if ($student->user && !$user->canViewProfile($student->user)) {
            abort(403, 'This user has locked his profile.');
        }
        
        $student->load([
            'user', 
            'classRoom', 
            'school', 
            'parent', 
            'grades.evaluation.subject',
            'grades.evaluation.evaluationType'
        ]);
        
        $users = User::orderBy('first_name')->get();
        
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
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $student->user_id],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'user_id' => ['nullable', 'exists:users,id'],
            'class_room_id' => ['nullable', 'exists:class_rooms,id'],
            'school_id' => ['required', 'exists:schools,id'],
            'selected_parent_id' => ['nullable'],
            'parent_id' => ['nullable', 'exists:parents,id'],
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

        // Mise à jour du compte utilisateur lié
        if ($student->user) {
            $student->user->update([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'status' => $validated['status'],
                'school_id' => $validated['school_id'],
            ]);

            if (!empty($validated['password'])) {
                $student->user->update(['password' => $validated['password']]);
            }
        }
        $validated['user_id'] = $student->user_id;

        // Do not allow changing admission_number or roll_number
        $validated['admission_number'] = $student->admission_number;
        $validated['roll_number'] = $student->roll_number;

        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($student->profile_photo && Storage::disk('public')->exists($student->profile_photo)) {
                Storage::disk('public')->delete($student->profile_photo);
            }
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
            } else {
                $validated['parent_id'] = null;
            }
        }

        $student->update($validated);
        // Notification to all school_admins of the student's school
        $schoolAdmins = \App\Models\User::where('role', 'school_admin')->where('school_id', $validated['school_id'])->get();
        $this->notificationService->sendToMany(
            $schoolAdmins,
            'warning',
            'Student Updated',
            'A student profile has been updated in the system.',
            route('students.show', $student)
        );
        return redirect()->route('students.index')->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        // Delete profile photo if exists
        if ($student->profile_photo && Storage::disk('public')->exists($student->profile_photo)) {
            Storage::disk('public')->delete($student->profile_photo);
        }
        try {
            $student->delete();
            // Notification to all school_admins of the student's school
            $schoolAdmins = \App\Models\User::where('role', 'school_admin')->where('school_id', $student->school_id)->get();
            $this->notificationService->sendToMany(
                $schoolAdmins,
                'error',
                'Student Deleted',
                'A student profile has been deleted from the system.'
            );
            return redirect()->route('students.index')->with('success', 'Student deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while deleting the student. Please try again.');
        }
    }
}
