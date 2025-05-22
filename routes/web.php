<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\StudentGradeController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\ClassRoomController;
use App\Http\Controllers\CalendarController;

Route::resource('teachers', TeacherController::class)->middleware('auth');
Route::resource('parents', ParentController::class)->middleware('auth');
Route::resource('students', StudentController::class)->middleware('auth');
Route::resource('schools', SchoolController::class)->middleware('auth');
Route::resource('class_rooms', ClassRoomController::class)->middleware('auth');
Route::resource('calendars', CalendarController::class)->middleware('auth');
Route::resource('evaluations', EvaluationController::class)->middleware('auth');
Route::resource('evaluations.student_grades', StudentGradeController::class)->shallow()->middleware('auth');

use App\Http\Controllers\SubjectController;

Route::middleware(['auth'])->group(function () {
    Route::resource('subjects', SubjectController::class);
});
