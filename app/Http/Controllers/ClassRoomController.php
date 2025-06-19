<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Http\Requests\ClassRoomRequest;
use Illuminate\Http\Request;

class ClassRoomController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(ClassRoom::class, 'class_room');
    }

    /**
     * Display a listing of the classroom resources.
     */
    public function index()
    {
        $classRooms = ClassRoom::with(['school', 'createdBy:id,first_name,last_name', 'updatedBy:id,first_name,last_name'])
            ->orderBy('name')
            ->get();
        return view('class_rooms.index', compact('classRooms'));
    }

    /**
     * Show the form for creating a new classroom resource.
     */
    public function create()
    {
        return view('class_rooms.create');
    }

    /**
     * Store a newly created classroom resource in storage.
     */
    public function store(ClassRoomRequest $request)
    {
        $validated = $request->validated();
        $validated['created_by'] = auth()->id();
        $validated['updated_by'] = auth()->id();

        $classRoom = ClassRoom::create($validated);
        // Notification
        app(\App\Services\NotificationService::class)->sendToRole(
            'admin',
            'New Class Created',
            'A new class has been created in the system.',
            'success',
            route('class_rooms.show', $classRoom)
        );
        return redirect()
            ->route('class_rooms.show', $classRoom)
            ->with('success', 'Classroom created successfully.');
    }

    /**
     * Display the specified classroom resource.
     */
    public function show(ClassRoom $classRoom)
    {
        $classRoom->load([
            'school',
            'teachers',
            'students',
            'evaluations',
            'subjects',
            'classRoomTeachers',
            'createdBy:id,first_name,last_name',
            'updatedBy:id,first_name,last_name',
        ]);
        
        return view('class_rooms.show', compact('classRoom'));
    }

    /**
     * Show the form for editing the specified classroom resource.
     */
    public function edit(ClassRoom $classRoom)
    {
        return view('class_rooms.edit', compact('classRoom'));
    }

    /**
     * Update the specified classroom resource in storage.
     */
    public function update(ClassRoomRequest $request, ClassRoom $classRoom)
    {
        $validated = $request->validated();
        $validated['updated_by'] = auth()->id();

        $classRoom->update($validated);
        // Notification
        app(\App\Services\NotificationService::class)->sendToRole(
            'admin',
            'Class Updated',
            'A class has been updated in the system.',
            'warning',
            route('class_rooms.show', $classRoom)
        );
        return redirect()
            ->route('class_rooms.show', $classRoom)
            ->with('success', 'Classroom updated successfully.');
    }

    /**
     * Remove the specified classroom resource from storage.
     */
    public function destroy(ClassRoom $classRoom)
    {
        if ($classRoom->students()->exists()) {
            return back()->with('error', 'Cannot delete classroom because it has associated students.');
        }

        $classRoom->delete();
        // Notification
        app(\App\Services\NotificationService::class)->sendToRole(
            'admin',
            'Class Deleted',
            'A class has been deleted from the system.',
            'error'
        );
        return redirect()
            ->route('class_rooms.index')
            ->with('success', 'Classroom deleted successfully.');
    }
}
