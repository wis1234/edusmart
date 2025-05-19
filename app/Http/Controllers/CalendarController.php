<?php

namespace App\Http\Controllers;

use App\Models\Calendar;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Calendar::class, 'calendar');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Calendar::class);
        $calendars = Calendar::all();
        return view('calendars.index', compact('calendars'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Calendar::class);
        return view('calendars.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Calendar::class);

        $validated = $request->validate([
            'academic_year' => 'required|string|max:255',
            'cohort' => 'required|string|max:255',
            'specialty' => 'required|string|max:255',
            'week' => 'required|string|max:255',
            'time' => 'required|string|max:255',
            'monday' => 'nullable|string|max:255',
            'tuesday' => 'nullable|string|max:255',
            'wednesday' => 'nullable|string|max:255',
            'thursday' => 'nullable|string|max:255',
            'friday' => 'nullable|string|max:255',
            'saturday' => 'nullable|string|max:255',
        ]);

        Calendar::create($validated);

        return redirect()->route('calendars.index')->with('success', 'Calendar created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Calendar $calendar)
    {
        $this->authorize('view', $calendar);
        return view('calendars.show', compact('calendar'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Calendar $calendar)
    {
        $this->authorize('update', $calendar);
        return view('calendars.edit', compact('calendar'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Calendar $calendar)
    {
        $this->authorize('update', $calendar);

        $validated = $request->validate([
            'academic_year' => 'required|string|max:255',
            'cohort' => 'required|string|max:255',
            'specialty' => 'required|string|max:255',
            'week' => 'required|string|max:255',
            'time' => 'required|string|max:255',
            'monday' => 'nullable|string|max:255',
            'tuesday' => 'nullable|string|max:255',
            'wednesday' => 'nullable|string|max:255',
            'thursday' => 'nullable|string|max:255',
            'friday' => 'nullable|string|max:255',
            'saturday' => 'nullable|string|max:255',
        ]);

        $calendar->update($validated);

        return redirect()->route('calendars.index')->with('success', 'Calendar updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Calendar $calendar)
    {
        $this->authorize('delete', $calendar);

        $calendar->delete();
        return redirect()->route('calendars.index')->with('success', 'Calendar deleted successfully.');
    }
}
