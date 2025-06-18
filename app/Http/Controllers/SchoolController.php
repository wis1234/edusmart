<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Http\Requests\SchoolRequest;
use Illuminate\Http\Request;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;

class SchoolController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
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

        // Notification de création d'école
        $this->notificationService->sendToRole(
            'admin',
            'Nouvelle école créée',
            "L'école {$school->name} a été créée avec succès.",
            'success',
            route('schools.show', $school)
        );

        return redirect()
            ->route('schools.index')
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

        $oldName = $school->name;
        $school->update($validated);

        // Notification de modification d'école
        $this->notificationService->sendToRole(
            'admin',
            'École modifiée',
            "L'école {$oldName} a été modifiée. Nouveau nom : {$school->name}",
            'warning',
            route('schools.show', $school)
        );

        return redirect()
            ->route('schools.index')
            ->with('success', 'School updated successfully.');
    }

    /**
     * Remove the specified school resource from storage.
     */
    public function destroy(School $school)
    {
        if ($school->students()->exists() || $school->teachers()->exists() || $school->classRooms()->exists()) {
            return redirect()
                ->route('schools.index')
                ->with('error', 'Cannot delete school because it has associated students, teachers, or classrooms.');
        }

        $schoolName = $school->name;
        $school->delete();

        // Notification de suppression d'école
        $this->notificationService->sendToRole(
            'admin',
            'École supprimée',
            "L'école {$schoolName} a été supprimée du système.",
            'error'
        );

        return redirect()
            ->route('schools.index')
            ->with('success', 'School deleted successfully.');
    }
}
