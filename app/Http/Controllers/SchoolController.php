<?php

namespace App\Http\Controllers;

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
        // Placeholder for listing school resources or settings
        return view('school.index');
    }

    /**
     * Show the form for creating a new school resource.
     */
    public function create()
    {
        $this->authorize('create', School::class);
        return view('school.create');
    }

    /**
     * Store a newly created school resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', School::class);
        // Placeholder for storing school resource
        // Validate and save data as needed

        return redirect()->route('school.index')->with('success', 'School resource created successfully.');
    }

    /**
     * Display the specified school resource.
     */
    public function show($id)
    {
        $this->authorize('view', School::class);
        // Placeholder for showing a specific school resource
        return view('school.show', compact('id'));
    }

    /**
     * Show the form for editing the specified school resource.
     */
    public function edit($id)
    {
        $this->authorize('update', School::class);
        // Placeholder for editing a specific school resource
        return view('school.edit', compact('id'));
    }

    /**
     * Update the specified school resource in storage.
     */
    public function update(Request $request, $id)
    {
        $this->authorize('update', School::class);
        // Placeholder for updating school resource
        // Validate and update data as needed

        return redirect()->route('school.index')->with('success', 'School resource updated successfully.');
    }

    /**
     * Remove the specified school resource from storage.
     */
    public function destroy($id)
    {
        $this->authorize('delete', School::class);
        // Placeholder for deleting school resource

        return redirect()->route('school.index')->with('success', 'School resource deleted successfully.');
    }
}
