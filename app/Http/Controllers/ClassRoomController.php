<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use Illuminate\Http\Request;

class ClassRoomController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(ClassRoom::class, 'classRoom');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', ClassRoom::class);
        $classRooms = ClassRoom::with('teacher', 'students')->get();
        return view('class_rooms.index', compact('classRooms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', ClassRoom::class);
        return view('class_rooms.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', ClassRoom::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|string|max:255',
            'teacher_id' => 'nullable|exists:users,id',
        ]);

        ClassRoom::create($validated);

        return redirect()->route('class_rooms.index')->with('success', 'Class room created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ClassRoom $classRoom)
    {
        $this->authorize('view', $classRoom);
        $classRoom->load('teacher', 'students');
        return view('class_rooms.show', compact('classRoom'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ClassRoom $classRoom)
    {
        $this->authorize('update', $classRoom);
        return view('class_rooms.edit', compact('classRoom'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ClassRoom $classRoom)
    {
        $this->authorize('update', $classRoom);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|string|max:255',
            'teacher_id' => 'nullable|exists:users,id',
        ]);

        $classRoom->update($validated);

        return redirect()->route('class_rooms.index')->with('success', 'Class room updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClassRoom $classRoom)
    {
        $this->authorize('delete', $classRoom);

        $classRoom->delete();
        return redirect()->route('class_rooms.index')->with('success', 'Class room deleted successfully.');
    }
}
