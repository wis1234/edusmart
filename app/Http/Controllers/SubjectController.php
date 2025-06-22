<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Http\Requests\SubjectRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Subject::class, 'subject');
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Subject::query();

        // Filter by school for school_admin
        if ($user->role === 'school_admin' && $user->school_id) {
            $query->where('school_id', $user->school_id);
        }

        // Filter by school for teachers (read-only access)
        if ($user->role === 'enseignant') {
            // Vérifier le school_id dans la table teachers
            $teacher = \App\Models\Teacher::where('user_id', $user->id)->first();
            if ($teacher && $teacher->school_id) {
                $query->where('school_id', $teacher->school_id);
            } else {
                // Si pas d'école assignée, ne montrer aucune matière
                $query->where('school_id', null);
            }
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('level', 'like', "%{$search}%");
            });
        }

        // Filter by level
        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }

        // Filter by credits range
        if ($request->filled('credits_min')) {
            $query->where('credits', '>=', $request->credits_min);
        }
        if ($request->filled('credits_max')) {
            $query->where('credits', '<=', $request->credits_max);
        }

        // Filter by hours per week
        if ($request->filled('hours_per_week')) {
            $query->where('hours_per_week', $request->hours_per_week);
        }

        $subjects = $query->with(['user', 'school'])->orderBy('name')->paginate(10);
        
        return view('subjects.index', compact('subjects'));
    }

    public function create()
    {
        return view('subjects.create');
    }

    public function store(SubjectRequest $request)
    {
        $validated = $request->validated();
        
        // Automatically assign the current user and their school
        $user = Auth::user();
        $validated['user_id'] = $user->id;
        
        // If user is school_admin, assign their school_id
        if ($user->role === 'school_admin' && $user->school_id) {
            $validated['school_id'] = $user->school_id;
        }

        $subject = Subject::create($validated);
        
        // Notification
        app(\App\Services\NotificationService::class)->sendToRole(
            'admin',
            'New Subject Created',
            'A new subject has been created in the system.',
            'success',
            route('subjects.show', $subject)
        );
        
        return redirect()->route('subjects.index')->with('success', 'Subject created successfully.');
    }

    public function show(Subject $subject)
    {
        // Additional security check for school_admin
        $user = Auth::user();
        if ($user->role === 'school_admin' && $user->school_id !== $subject->school_id) {
            abort(403, 'You can only view subjects from your assigned school.');
        }
        
        // Additional security check for teachers
        if ($user->role === 'enseignant') {
            $teacher = \App\Models\Teacher::where('user_id', $user->id)->first();
            if (!$teacher || $teacher->school_id !== $subject->school_id) {
                abort(403, 'You can only view subjects from your assigned school.');
            }
        }
        
        $subject->load(['user', 'school']);
        return view('subjects.show', compact('subject'));
    }

    public function edit(Subject $subject)
    {
        // Additional security check for school_admin
        $user = Auth::user();
        if ($user->role === 'school_admin' && $user->school_id !== $subject->school_id) {
            abort(403, 'You can only edit subjects from your assigned school.');
        }
        
        // Teachers have read-only access, so they cannot edit
        if ($user->role === 'enseignant') {
            abort(403, 'Teachers have read-only access to subjects.');
        }
        
        $subject->load(['user', 'school']);
        return view('subjects.edit', compact('subject'));
    }

    public function update(SubjectRequest $request, Subject $subject)
    {
        $validated = $request->validated();
        
        // Keep the original user_id and school_id (these should not be changed during update)
        // Only update the subject-specific fields
        
        // Additional security check for school_admin
        $user = Auth::user();
        if ($user->role === 'school_admin' && $user->school_id !== $subject->school_id) {
            abort(403, 'You can only edit subjects from your assigned school.');
        }
        
        // Teachers have read-only access, so they cannot update
        if ($user->role === 'enseignant') {
            abort(403, 'Teachers have read-only access to subjects.');
        }
        
        $subject->update($validated);
        
        // Notification
        app(\App\Services\NotificationService::class)->sendToRole(
            'admin',
            'Subject Updated',
            'A subject has been updated in the system.',
            'warning',
            route('subjects.show', $subject)
        );
        
        return redirect()->route('subjects.index')->with('success', 'Subject updated successfully.');
    }

    public function destroy(Subject $subject)
    {
        // Additional security check for school_admin
        $user = Auth::user();
        if ($user->role === 'school_admin' && $user->school_id !== $subject->school_id) {
            abort(403, 'You can only delete subjects from your assigned school.');
        }
        
        // Teachers have read-only access, so they cannot delete
        if ($user->role === 'enseignant') {
            abort(403, 'Teachers have read-only access to subjects.');
        }
        
        $subject->delete();
        
        // Notification
        app(\App\Services\NotificationService::class)->sendToRole(
            'admin',
            'Subject Deleted',
            'A subject has been deleted from the system.',
            'error'
        );
        
        return redirect()->route('subjects.index')->with('success', 'Subject deleted successfully.');
    }
}
