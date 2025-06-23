<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParentController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class, 'parent');
    }

    /**
     * Display a listing of the parents.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);
        
        $user = auth()->user();
        $query = User::role('parent')->withCount('students');
        
        // Si l'utilisateur est un school_admin, filtrer par les parents des étudiants de son école
        if ($user->role === 'school_admin' && $user->school_id) {
            $query->whereHas('students', function($q) use ($user) {
                $q->where('school_id', $user->school_id);
            });
        }
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        // Filter by profession
        if ($request->filled('profession')) {
            $query->where('profession', $request->profession);
        }
        
        $parents = $query->paginate(10);
        
        return view('parents.index', compact('parents'));
    }

    /**
     * Show the form for creating a new parent.
     */
    public function create()
    {
        $this->authorize('create', User::class);
        return view('parents.create');
    }

    /**
     * Store a newly created parent in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        try {
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'nullable|string|max:255',
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string',
                'profession' => 'nullable|string|max:255',
                'status' => 'required|in:active,inactive',
                'role' => 'required|in:parent,admin,teacher,student',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8|confirmed',
                'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'date_of_birth' => 'nullable|date',
                'gender' => 'nullable|in:male,female,other',
            ]);

            $parentData = [
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'address' => $validated['address'] ?? null,
                'profession' => $validated['profession'] ?? null,
                'status' => $validated['status'],
                'role' => $validated['role'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
                'date_of_birth' => $validated['date_of_birth'] ?? null,
                'gender' => $validated['gender'] ?? null,
            ];

            // Handle profile photo upload
            if ($request->hasFile('profile_photo')) {
                $photoPath = $request->file('profile_photo')->store('profile-photos', 'public');
                $parentData['profile_photo'] = $photoPath;
            }

            $parentUser = User::create($parentData);
            $parentUser->assignRole('parent');

            // Créer l'entrée dans la table parents
            \App\Models\ParentModel::create([
                'user_id' => $parentUser->id,
                'phone' => $parentUser->phone,
                'address' => $parentUser->address,
                'date_of_birth' => $parentUser->date_of_birth,
                'gender' => $parentUser->gender,
                'profile_photo' => $parentUser->profile_photo,
                'status' => $parentUser->status,
            ]);

            return redirect()->route('parents.index')->with('success', 'Parent created successfully.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred while creating the parent. Please try again.')
                ->withInput();
        }
    }

    /**
     * Display the specified parent.
     */
    public function show(User $parent)
    {
        $user = Auth::user();
        
        // Vérifier si l'utilisateur peut voir le profil selon la logique de verrouillage
        if (!$user->canViewProfile($parent)) {
            abort(403, 'This user has locked his profile.');
        }
        
        $parent->load(['students.classRoom', 'students.school']);
        
        return view('parents.show', compact('parent'));
    }

    /**
     * Show the form for editing the specified parent.
     */
    public function edit(User $parent)
    {
        $this->authorize('update', $parent);
        
        $user = auth()->user();
        
        // Si l'utilisateur est un school_admin, vérifier que le parent a des étudiants dans son école
        if ($user->role === 'school_admin' && $user->school_id) {
            $hasStudentsInSchool = $parent->students()->where('school_id', $user->school_id)->exists();
            if (!$hasStudentsInSchool) {
                abort(403, 'You can only edit parents of students in your school.');
            }
        }
        
        return view('parents.edit', compact('parent'));
    }

    /**
     * Update the specified parent in storage.
     */
    public function update(Request $request, User $parent)
    {
        $this->authorize('update', $parent);

        $user = auth()->user();
        
        // Si l'utilisateur est un school_admin, vérifier que le parent a des étudiants dans son école
        if ($user->role === 'school_admin' && $user->school_id) {
            $hasStudentsInSchool = $parent->students()->where('school_id', $user->school_id)->exists();
            if (!$hasStudentsInSchool) {
                abort(403, 'You can only update parents of students in your school.');
            }
        }

        try {
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'nullable|string|max:255',
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string',
                'profession' => 'nullable|string|max:255',
                'status' => 'required|in:active,inactive',
                'role' => 'required|in:parent,admin,teacher,student',
                'email' => 'required|email|unique:users,email,' . $parent->id,
                'password' => 'nullable|string|min:8|confirmed',
                'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'date_of_birth' => 'nullable|date',
                'gender' => 'nullable|in:male,female,other',
            ]);

            $parent->first_name = $validated['first_name'];
            $parent->last_name = $validated['last_name'] ?? null;
            $parent->phone = $validated['phone'] ?? null;
            $parent->address = $validated['address'] ?? null;
            $parent->profession = $validated['profession'] ?? null;
            $parent->status = $validated['status'];
            $parent->role = $validated['role'];
            $parent->email = $validated['email'];
            $parent->date_of_birth = $validated['date_of_birth'] ?? null;
            $parent->gender = $validated['gender'] ?? null;
            if (!empty($validated['password'])) {
                $parent->password = bcrypt($validated['password']);
            }
            if ($request->hasFile('profile_photo')) {
                $photoPath = $request->file('profile_photo')->store('profile-photos', 'public');
                $parent->profile_photo = $photoPath;
            }
            $parent->save();

            // Mettre à jour la table parents
            $parentModel = $parent->parentModel ?? new \App\Models\ParentModel(['user_id' => $parent->id]);
            $parentModel->phone = $parent->phone;
            $parentModel->address = $parent->address;
            $parentModel->date_of_birth = $parent->date_of_birth;
            $parentModel->gender = $parent->gender;
            $parentModel->profile_photo = $parent->profile_photo;
            $parentModel->status = $parent->status;
            $parentModel->save();

            return redirect()->route('parents.index')->with('success', 'Parent updated successfully.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'An error occurred while updating the parent. Please try again.')
                ->withInput();
        }
    }

    /**
     * Remove the specified parent from storage.
     */
    public function destroy(User $parent)
    {
        $this->authorize('delete', $parent);

        $user = auth()->user();
        
        // Si l'utilisateur est un school_admin, vérifier que le parent a des étudiants dans son école
        if ($user->role === 'school_admin' && $user->school_id) {
            $hasStudentsInSchool = $parent->students()->where('school_id', $user->school_id)->exists();
            if (!$hasStudentsInSchool) {
                abort(403, 'You can only delete parents of students in your school.');
            }
        }

        $parent->delete();
        return redirect()->route('parents.index')->with('success', 'Parent deleted successfully.');
    }
}
