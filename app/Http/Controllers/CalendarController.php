<?php

namespace App\Http\Controllers;

use App\Models\Calendar;
use Illuminate\Http\Request;
use App\Models\School;
use App\Models\ClassRoom;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Support\Facades\DB;
use App\Services\NotificationService;

class CalendarController extends Controller
{
    protected $notificationService;
    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
        $this->authorizeResource(Calendar::class, 'calendar');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Calendar::class);

        $query = Calendar::with(['school', 'classRoom', 'subject', 'teacher']);

        if ($request->filled('school')) {
            $query->where('school_id', $request->input('school'));
        }
        if ($request->filled('class_room')) {
            $query->where('class_room_id', $request->input('class_room'));
        }
        if ($request->filled('subject')) {
            $query->where('subject_id', $request->input('subject'));
        }
        if ($request->filled('academic_year')) {
            $query->where('academic_year', $request->input('academic_year'));
        }

        $calendars = $query->orderByDesc('id')->paginate(12)->withQueryString();

        $schools = \App\Models\School::all();
        $classRooms = \App\Models\ClassRoom::all();
        $subjects = \App\Models\Subject::all();

        return view('calendars.index', compact('calendars', 'schools', 'classRooms', 'subjects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Calendar::class);
        $user = auth()->user();
        if ($user->isSchoolAdmin()) {
            $schools = School::where('id', $user->school_id)->get();
            $classRooms = ClassRoom::where('school_id', $user->school_id)->get();
            $subjects = Subject::where('school_id', $user->school_id)->get();
        } else {
            $schools = School::all();
            $classRooms = ClassRoom::all();
            $subjects = Subject::all();
        }
        $teachers = Teacher::all();
        return view('calendars.create', compact('schools', 'classRooms', 'subjects', 'teachers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Calendar::class);

        // Validation des champs globaux
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'class_room_id' => 'required|exists:class_rooms,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'academic_year' => 'required|string|max:255',
            'week_number' => 'nullable|integer|min:1|max:53',
            'week_schedule' => 'required|array',
        ]);

        $weekSchedule = $request->input('week_schedule', []);
        $days = ['monday','tuesday','wednesday','thursday','friday','saturday'];
        $errors = [];

        // Validation interne (pas de chevauchement pour un même jour)
        foreach ($days as $day) {
            $slots = $weekSchedule[$day] ?? [];
            foreach ($slots as $i => $slot) {
                $start = $slot['start_time'] ?? null;
                $end = $slot['end_time'] ?? null;
                // Si les deux sont vides, on ignore ce créneau
                if (empty($start) && empty($end)) {
                    continue;
                }
                // Si un seul est rempli, erreur
                if (empty($start) || empty($end)) {
                    $errors["week_schedule.$day.$i.start_time"] = 'Both start and end time are required for ' . ucfirst($day);
                    continue;
                }
                if (!preg_match('/^\d{2}:\d{2}$/', $start)) {
                    $errors["week_schedule.$day.$i.start_time"] = 'Invalid start time format for ' . ucfirst($day);
                }
                if (!preg_match('/^\d{2}:\d{2}$/', $end)) {
                    $errors["week_schedule.$day.$i.end_time"] = 'Invalid end time format for ' . ucfirst($day);
                }
                if (strtotime($end) <= strtotime($start)) {
                    $errors["week_schedule.$day.$i.end_time"] = 'End time must be after start time for ' . ucfirst($day);
                }
                // Chevauchement interne
                foreach ($slots as $j => $other) {
                    if ($i !== $j) {
                        $oStart = $other['start_time'] ?? null;
                        $oEnd = $other['end_time'] ?? null;
                        if ($oStart && $oEnd && max($start, $oStart) < min($end, $oEnd)) {
                            $errors["week_schedule.$day.$i.start_time"] = 'Time slot overlaps with another for ' . ucfirst($day);
                        }
                    }
                }
            }
        }

        // Conflit externe (autres emplois du temps)
        $conflict = Calendar::where('school_id', $validated['school_id'])
            ->where('class_room_id', $validated['class_room_id'])
            ->where('subject_id', $validated['subject_id'])
            ->where('teacher_id', $validated['teacher_id'])
            ->where('academic_year', $validated['academic_year'])
            ->when($validated['week_number'] ?? null, function($q) use ($validated) {
                $q->where('week_number', $validated['week_number']);
            }, function($q) {
                $q->whereNull('week_number');
            })
            ->exists();
        if ($conflict) {
            $errors['week_schedule'] = 'A schedule already exists for this class, subject, teacher, year, and week.';
        }

        if (!empty($errors)) {
            return back()->withInput()->withErrors($errors);
        }

        $calendar = new Calendar();
        $calendar->fill($validated);
        $calendar->week_schedule = $weekSchedule;
        $calendar->created_by = auth()->id();
        $calendar->updated_by = auth()->id();
        $calendar->save();

        // Notification
        $this->notificationService->sendToRole(
            'admin',
            'New Calendar Created',
            'A new calendar entry has been created in the system.',
            'success',
            route('calendars.show', $calendar)
        );
        return redirect()->route('calendars.index')->with('success', 'Schedule created successfully.');
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
        $user = auth()->user();
        if ($user->isSchoolAdmin()) {
            $schools = School::where('id', $user->school_id)->get();
            $classRooms = ClassRoom::where('school_id', $user->school_id)->get();
            $subjects = Subject::where('school_id', $user->school_id)->get();
        } else {
            $schools = School::all();
            $classRooms = ClassRoom::all();
            $subjects = Subject::all();
        }
        $teachers = Teacher::all();

        // Charger tous les créneaux de la semaine pour la même classe/matière/enseignant/année/semaine
        $weekSchedules = [];
        $days = ['monday','tuesday','wednesday','thursday','friday','saturday'];
        $allWeek = Calendar::where('school_id', $calendar->school_id)
            ->where('class_room_id', $calendar->class_room_id)
            ->where('subject_id', $calendar->subject_id)
            ->where('teacher_id', $calendar->teacher_id)
            ->where('academic_year', $calendar->academic_year)
            ->when($calendar->week_number, function($q) use ($calendar) {
                $q->where('week_number', $calendar->week_number);
            }, function($q) {
                $q->whereNull('week_number');
            })
            ->get();
        foreach ($days as $day) {
            $slot = $allWeek->firstWhere('weekday', $day);
            $weekSchedules[$day] = [
                'start_time' => $slot ? $slot->start_time : '',
                'end_time' => $slot ? $slot->end_time : '',
            ];
        }

        return view('calendars.edit', compact('calendar', 'schools', 'classRooms', 'subjects', 'teachers', 'weekSchedules'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Calendar $calendar)
    {
        $this->authorize('update', $calendar);

        // Validation des champs globaux
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'class_room_id' => 'required|exists:class_rooms,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'academic_year' => 'required|string|max:255',
            'week_number' => 'nullable|integer|min:1|max:53',
            'week_schedule' => 'required|array',
        ]);

        $weekSchedule = $request->input('week_schedule', []);
        $days = ['monday','tuesday','wednesday','thursday','friday','saturday'];
        $errors = [];

        // Validation interne (pas de chevauchement pour un même jour)
        foreach ($days as $day) {
            $slots = $weekSchedule[$day] ?? [];
            foreach ($slots as $i => $slot) {
                $start = $slot['start_time'] ?? null;
                $end = $slot['end_time'] ?? null;
                // Si les deux sont vides, on ignore ce créneau
                if (empty($start) && empty($end)) {
                    continue;
                }
                // Si un seul est rempli, erreur
                if (empty($start) || empty($end)) {
                    $errors["week_schedule.$day.$i.start_time"] = 'Both start and end time are required for ' . ucfirst($day);
                    continue;
                }
                if (!preg_match('/^\d{2}:\d{2}$/', $start)) {
                    $errors["week_schedule.$day.$i.start_time"] = 'Invalid start time format for ' . ucfirst($day);
                }
                if (!preg_match('/^\d{2}:\d{2}$/', $end)) {
                    $errors["week_schedule.$day.$i.end_time"] = 'Invalid end time format for ' . ucfirst($day);
                }
                if (strtotime($end) <= strtotime($start)) {
                    $errors["week_schedule.$day.$i.end_time"] = 'End time must be after start time for ' . ucfirst($day);
                }
                // Chevauchement interne
                foreach ($slots as $j => $other) {
                    if ($i !== $j) {
                        $oStart = $other['start_time'] ?? null;
                        $oEnd = $other['end_time'] ?? null;
                        if ($oStart && $oEnd && max($start, $oStart) < min($end, $oEnd)) {
                            $errors["week_schedule.$day.$i.start_time"] = 'Time slot overlaps with another for ' . ucfirst($day);
                        }
                    }
                }
            }
        }

        // Conflit externe (autres emplois du temps, hors celui-ci)
        $conflict = Calendar::where('school_id', $validated['school_id'])
            ->where('class_room_id', $validated['class_room_id'])
            ->where('subject_id', $validated['subject_id'])
            ->where('teacher_id', $validated['teacher_id'])
            ->where('academic_year', $validated['academic_year'])
            ->when($validated['week_number'] ?? null, function($q) use ($validated) {
                $q->where('week_number', $validated['week_number']);
            }, function($q) {
                $q->whereNull('week_number');
            })
            ->where('id', '!=', $calendar->id)
            ->exists();
        if ($conflict) {
            $errors['week_schedule'] = 'A schedule already exists for this class, subject, teacher, year, and week.';
        }

        if (!empty($errors)) {
            return back()->withInput()->withErrors($errors);
        }

        $calendar->fill($validated);
        $calendar->week_schedule = $weekSchedule;
        $calendar->updated_by = auth()->id();
        $calendar->save();

        // Notification
        $this->notificationService->sendToRole(
            'admin',
            'Calendar Updated',
            'A calendar entry has been updated in the system.',
            'warning',
            route('calendars.show', $calendar)
        );
        return redirect()->route('calendars.index')->with('success', 'Schedule updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Calendar $calendar)
    {
        $this->authorize('delete', $calendar);
        try {
            $calendar->delete();
            // Notification
            $this->notificationService->sendToRole(
                'admin',
                'Calendar Deleted',
                'A calendar entry has been deleted from the system.',
                'error'
            );
            return redirect()->route('calendars.index')->with('success', 'Schedule deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while deleting the calendar. Please try again.');
        }
    }

    /**
     * Retourne les classes affectées à un enseignant (JSON) via la table pivot class_room_teacher
     */
    public function getTeacherClassRooms($teacherId)
    {
        $classRooms = DB::table('class_room_teacher')
            ->join('class_rooms', 'class_room_teacher.class_room_id', '=', 'class_rooms.id')
            ->where('class_room_teacher.teacher_id', $teacherId)
            ->select('class_rooms.id', 'class_rooms.name')
            ->distinct()
            ->get();
        return response()->json($classRooms);
    }

    /**
     * Retourne les matières enseignées par un enseignant dans une classe donnée (JSON) via la table pivot class_room_teacher
     */
    public function getTeacherSubjectsForClassRoom($teacherId, $classRoomId)
    {
        $subjects = DB::table('class_room_teacher')
            ->join('subjects', 'class_room_teacher.subject_id', '=', 'subjects.id')
            ->where('class_room_teacher.teacher_id', $teacherId)
            ->where('class_room_teacher.class_room_id', $classRoomId)
            ->select('subjects.id', 'subjects.name')
            ->distinct()
            ->get();
        return response()->json($subjects);
    }
}
