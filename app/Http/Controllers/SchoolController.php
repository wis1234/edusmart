<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Http\Requests\SchoolRequest;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(School::class, 'school');
    }

    /**
     * Display a listing of the school resources.
     */
    public function index()
    {
        $schools = School::with(['createdBy:id,first_name,last_name', 'updatedBy:id,first_name,last_name'])
            ->orderBy('name')
            ->get();
        return view('schools.index', compact('schools'));
    }

    /**
     * Show the form for creating a new school resource.
     */
    public function create()
    {
        return view('schools.create');
    }

    /**
     * Store a newly created school resource in storage.
     */
    public function store(SchoolRequest $request)
    {
        $validated = $request->validated();
        $validated['created_by'] = auth()->id();
        $validated['updated_by'] = auth()->id();

        $school = School::create($validated);

        return redirect()
            ->route('schools.show', $school)
            ->with('success', 'School created successfully.');
    }

    /**
     * Display the specified school resource.
     */
    public function show(School $school)
    {
        $school->load([
            'createdBy:id,first_name,last_name',
            'updatedBy:id,first_name,last_name',
            'teachers',
            'students',
            'classRooms'
        ]);
        
        return view('schools.show', compact('school'));
    }

    /**
     * Show the form for editing the specified school resource.
     */
    public function edit(School $school)
    {
        return view('schools.edit', compact('school'));
    }

    /**
     * Update the specified school resource in storage.
     */
    public function update(SchoolRequest $request, School $school)
    {
        $validated = $request->validated();
        $validated['updated_by'] = auth()->id();

        $school->update($validated);

        return redirect()
            ->route('schools.show', $school)
            ->with('success', 'School updated successfully.');
    }

    /**
     * Remove the specified school resource from storage.
     */
    public function destroy(School $school)
    {
        if ($school->students()->exists() || $school->teachers()->exists() || $school->classRooms()->exists()) {
            return back()->with('error', 'Cannot delete school because it has associated students, teachers, or classrooms.');
        }

        $school->delete();

        return redirect()
            ->route('schools.index')
            ->with('success', 'School deleted successfully.');
    }
}
