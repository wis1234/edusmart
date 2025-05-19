<?php

namespace App\Http\Controllers;

use App\Models\School;
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
        $this->authorize('viewAny', School::class);
        $schools = School::all();
        return view('schools.index', compact('schools'));
    }

    /**
     * Show the form for creating a new school resource.
     */
    public function create()
    {
        $this->authorize('create', School::class);
        return view('schools.create');
    }

    /**
     * Store a newly created school resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', School::class);
        // Placeholder for storing school resource
        // Validate and save data as needed

        return redirect()->route('schools.index')->with('success', 'School resource created successfully.');
    }

    /**
     * Display the specified school resource.
     */
    public function show(School $school)
    {
        $this->authorize('view', $school);
        return view('schools.show', compact('school'));
    }

    /**
     * Show the form for editing the specified school resource.
     */
    public function edit(School $school)
    {
        $this->authorize('update', $school);
        return view('schools.edit', compact('school'));
    }

    /**
     * Update the specified school resource in storage.
     */
    public function update(Request $request, School $school)
    {
        $this->authorize('update', $school);
        // Placeholder for updating school resource
        // Validate and update data as needed

        return redirect()->route('schools.index')->with('success', 'School resource updated successfully.');
    }

    /**
     * Remove the specified school resource from storage.
     */
    public function destroy(School $school)
    {
        $this->authorize('delete', $school);
        // Placeholder for deleting school resource

        return redirect()->route('schools.index')->with('success', 'School resource deleted successfully.');
    }
}
