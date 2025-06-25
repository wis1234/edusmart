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

class RegisteredUserController extends Controller
{
    /**
     * Show the registration view.
     */
    public function create()
    {
        $schools = \App\Models\School::orderBy('name')->get(['id', 'name']);
        return view('auth.register', compact('schools'));
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
            'blood_group' => 'nullable|string|max:20',
            'emergency_contact' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'medical_conditions' => 'nullable|string|max:255',
            'profile_photo' => 'nullable|image|max:2048',
        ];
        $teacherRules = [
            'speciality' => 'required|string|max:255',
            'teacher_phone' => 'required|string|max:20',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'address' => 'required|string|max:255',
            'grade' => 'required|string|max:100',
            'profile_photo' => 'nullable|image|max:2048',
        ];
        $parentRules = [
            'profession' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'address' => 'nullable|string|max:255',
            'profile_photo' => 'nullable|image|max:2048',
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
        if ($request->hasFile('profile_photo')) {
            $profilePhotoPath = $request->file('profile_photo')->store('profile_photos', 'public');
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
            Teacher::create([
                'user_id' => $user->id,
                'teacher_firstname' => $request->first_name,
                'teacher_lastname' => $request->last_name,
                'teacher_email' => $request->email,
                'speciality' => $request->speciality,
                'teacher_phone' => $request->teacher_phone,
                'school_id' => $request->school_id,
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'address' => $request->address,
                'grade' => $request->grade,
                'profile_photo' => $profilePhotoPath,
            ]);
        } elseif ($request->role === 'parent') {
            ParentModel::create([
                'user_id' => $user->id,
                'profession' => $request->profession,
                'phone' => $request->phone,
                'date_of_birth' => $request->date_of_birth,
                'address' => $request->address,
                'profile_photo' => $profilePhotoPath,
            ]);
        } elseif ($request->role === 'student') {
            Student::create([
                'user_id' => $user->id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'school_id' => $request->school_id,
                'admission_date' => $request->admission_date,
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'academic_year' => $request->academic_year,
                'status' => 'inactive',
                'blood_group' => $request->blood_group,
                'emergency_contact' => $request->emergency_contact,
                'address' => $request->address,
                'medical_conditions' => $request->medical_conditions,
                'profile_photo' => $profilePhotoPath,
            ]);
        }

        event(new Registered($user));

        return redirect()->route('registration.success');
    }
}
