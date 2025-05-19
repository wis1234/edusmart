<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;
use Illuminate\Database\QueryException;
use Inertia\Inertia;

class TeacherController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Authorization is handled at the method level
        // for more granular control
    }

    /**
     * Display a listing of the teachers.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $this->authorize('viewAny', User::class);

        $teachers = User::role('enseignant')
            ->orderBy('name')
            ->paginate(15);

        return view('teachers.index', [
            'teachers' => $teachers,
            'can' => [
                'create' => $this->authorize('create', User::class, [], true),
            ],
            'flash' => [
                'success' => session('success'),
                'error' => session('error'),
            ],
        ]);
    }

    /**
     * Show the form for creating a new teacher.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $this->authorize('create', User::class);
        return view('teachers.create');
    }

    /**
     * Store a newly created teacher in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'confirmed', Password::defaults()],
            ]);

            $teacher = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            $teacher->assignRole('enseignant');
            
            Log::info('Teacher created', ['id' => $teacher->id, 'email' => $teacher->email]);

            return redirect()
                ->route('teachers.index')
                ->with('success', 'Teacher created successfully.');
                
        } catch (QueryException $e) {
            Log::error('Failed to create teacher', [
                'error' => $e->getMessage(),
                'input' => $request->except('password')
            ]);
            
            return back()
                ->withInput($request->except('password'))
                ->with('error', 'An error occurred while creating the teacher.');
        }
    }

    /**
     * Display the specified teacher.
     *
     * @param  \App\Models\User  $teacher
     * @return \Illuminate\View\View
     */
    public function show(User $teacher)
    {
        $this->authorize('view', $teacher);
        
        // Eager load relevant relationships if needed
        $teacher->load(['roles', 'permissions']);
        
        return view('teachers.show', compact('teacher'));
    }

    /**
     * Show the form for editing the specified teacher.
     *
     * @param  \App\Models\User  $teacher
     * @return \Illuminate\View\View
     */
    public function edit(User $teacher)
    {
        $this->authorize('update', $teacher);
        return view('teachers.edit', compact('teacher'));
    }

    /**
     * Update the specified teacher in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $teacher
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $teacher)
    {
        $this->authorize('update', $teacher);

        try {
            $rules = [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $teacher->id],
            ];
            
            // Only validate password if it's provided
            if ($request->filled('password')) {
                $rules['password'] = ['confirmed', Password::defaults()];
            }
            
            $validated = $request->validate($rules);

            // Update only what has changed
            $teacher->fill([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);
            
            if ($request->filled('password')) {
                $teacher->password = Hash::make($validated['password']);
            }
            
            $teacher->save();

            Log::info('Teacher updated', ['id' => $teacher->id]);
            
            return redirect()
                ->route('teachers.index')
                ->with('success', 'Teacher updated successfully.');
                
        } catch (QueryException $e) {
            Log::error('Failed to update teacher', [
                'id' => $teacher->id,
                'error' => $e->getMessage()
            ]);
            
            return back()
                ->withInput($request->except('password'))
                ->with('error', 'An error occurred while updating the teacher.');
        }
    }

    /**
     * Remove the specified teacher from storage.
     *
     * @param  \App\Models\User  $teacher
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $teacher)
    {
        $this->authorize('delete', $teacher);

        try {
            // Store teacher info for logging before deletion
            $teacherId = $teacher->id;
            $teacherEmail = $teacher->email;
            
            $teacher->delete();
            
            Log::info('Teacher deleted', ['id' => $teacherId, 'email' => $teacherEmail]);
            
            return redirect()
                ->route('teachers.index')
                ->with('success', 'Teacher deleted successfully.');
                
        } catch (QueryException $e) {
            Log::error('Failed to delete teacher', [
                'id' => $teacher->id,
                'error' => $e->getMessage()
            ]);
            
            return back()->with('error', 'An error occurred while deleting the teacher.');
        }
    }
}