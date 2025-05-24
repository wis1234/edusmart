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

use App\Http\Controllers\Ecommerce\ProductController;
use App\Http\Controllers\Ecommerce\OrderController;

// Ecommerce routes
Route::prefix('ecommerce')->name('ecommerce.')->group(function () {
    Route::get('/products', [ProductController::class, 'index'])->name('index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('create');
    Route::post('/products', [ProductController::class, 'store'])->name('store');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('show');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('destroy');

    Route::post('/cart', [OrderController::class, 'addToCart'])->name('cart.add');
    Route::get('/cart', [OrderController::class, 'viewCart'])->name('cart.view');
    Route::post('/checkout', [OrderController::class, 'checkout'])->name('checkout');
});
