<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Middleware to restrict access to admin role and specific user email
        $this->middleware(function ($request, $next) {
            $user = Auth::user();
            if (!$user->hasRole('admin') && $user->email !== 'ronaldoagbohou@gmail.com') {
                abort(403, 'Unauthorized');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $subjects = Subject::orderBy('name')->paginate(15);
        return view('subjects.index', compact('subjects'));
    }

    public function create()
    {
        return view('subjects.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'credits' => 'nullable|integer|min:0',
            'level' => 'nullable|string|max:50',
            'hours_per_week' => 'nullable|integer|min:0',
            'is_active' => 'string',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Subject::create($validated);

        return redirect()->route('subjects.index')->with('success', 'Subject created successfully.');
    }

    public function show(Subject $subject)
    {
        return view('subjects.show', compact('subject'));
    }

    public function edit(Subject $subject)
    {
        return view('subjects.edit', compact('subject'));
    }

    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'credits' => 'nullable|integer|min:0',
            'level' => 'nullable|string|max:50',
            'hours_per_week' => 'nullable|integer|min:0',
            'is_active' => 'string',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $subject->update($validated);

        return redirect()->route('subjects.index')->with('success', 'Subject updated successfully.');
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();

        return redirect()->route('subjects.index')->with('success', 'Subject deleted successfully.');
    }
}
