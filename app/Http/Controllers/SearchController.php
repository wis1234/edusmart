<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\ParentModel;
use App\Models\School;
use App\Models\Subject;
use App\Models\ClassRoom;
use App\Models\Evaluation;
use App\Models\Guardian;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');
        if (!$query) {
            return view('search.global', ['results' => [], 'query' => '']);
        }

        $results = [];

        // Users
        $users = User::where('first_name', 'like', "%$query%")
            ->orWhere('last_name', 'like', "%$query%")
            ->orWhere('email', 'like', "%$query%")
            ->limit(5)->get();
        if ($users->count()) {
            $results['Users'] = $users;
        }

        // Students
        $students = Student::where('first_name', 'like', "%$query%")
            ->orWhere('last_name', 'like', "%$query%")
            ->orWhere('admission_number', 'like', "%$query%")
            ->limit(5)->get();
        if ($students->count()) {
            $results['Students'] = $students;
        }

        // Teachers
        $teachers = Teacher::where('teacher_firstname', 'like', "%$query%")
            ->orWhere('teacher_lastname', 'like', "%$query%")
            ->limit(5)->get();
        if ($teachers->count()) {
            $results['Teachers'] = $teachers;
        }

        // Parents (search via related user)
        $parents = \App\Models\ParentModel::with('user')
            ->whereHas('user', function($q) use ($query) {
                $q->where('first_name', 'like', "%$query%")
                  ->orWhere('last_name', 'like', "%$query%")
                  ->orWhere('email', 'like', "%$query%")
                  ;
            })
            ->limit(5)->get();
        if ($parents->count()) {
            $results['Parents'] = $parents;
        }

        // Guardians (search via related user)
        $guardians = \App\Models\Guardian::with('user')
            ->whereHas('user', function($q) use ($query) {
                $q->where('first_name', 'like', "%$query%")
                  ->orWhere('last_name', 'like', "%$query%")
                  ->orWhere('email', 'like', "%$query%")
                  ;
            })
            ->limit(5)->get();
        if ($guardians->count()) {
            $results['Guardians'] = $guardians;
        }

        // Schools
        $schools = School::where('name', 'like', "%$query%")
            ->orWhere('code', 'like', "%$query%")
            ->orWhere('city', 'like', "%$query%")
            ->limit(5)->get();
        if ($schools->count()) {
            $results['Schools'] = $schools;
        }

        // Subjects
        $subjects = Subject::where('name', 'like', "%$query%")
            ->orWhere('code', 'like', "%$query%")
            ->orWhere('description', 'like', "%$query%")
            ->limit(5)->get();
        if ($subjects->count()) {
            $results['Subjects'] = $subjects;
        }

        // ClassRooms
        $classRooms = ClassRoom::where('name', 'like', "%$query%")
            ->orWhere('grade_level', 'like', "%$query%")
            ->limit(5)->get();
        if ($classRooms->count()) {
            $results['ClassRooms'] = $classRooms;
        }

        // Evaluations (no 'title' field, search on term, academic_year, notes, subject name, classRoom name)
        $evaluations = Evaluation::with(['subject', 'classRoom'])
            ->where('term', 'like', "%$query%")
            ->orWhere('academic_year', 'like', "%$query%")
            ->orWhere('notes', 'like', "%$query%")
            ->orWhereHas('subject', function($q) use ($query) {
                $q->where('name', 'like', "%$query%")
                  ->orWhere('code', 'like', "%$query%")
                  ;
            })
            ->orWhereHas('classRoom', function($q) use ($query) {
                $q->where('name', 'like', "%$query%")
                  ->orWhere('grade_level', 'like', "%$query%")
                  ;
            })
            ->limit(5)->get();
        if ($evaluations->count()) {
            $results['Evaluations'] = $evaluations;
        }

        return view('search.global', [
            'results' => $results,
            'query' => $query,
        ]);
    }
} 