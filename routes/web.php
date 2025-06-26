<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotificationController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\StudentGradeController;
use App\Http\Controllers\DashboardController;
use App\Models\Activity;
use App\Http\Controllers\ActivityController;
use Spatie\Permission\Middlewares\RoleMiddleware;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/dashboard/{type}', [DashboardController::class, 'getContent'])->name('dashboard.content');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Settings routes
    Route::get('/settings', [App\Http\Controllers\SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/toggle-two-factor', [App\Http\Controllers\SettingsController::class, 'toggleTwoFactor'])->name('settings.toggle-two-factor');
    Route::post('/settings/toggle-profile-lock', [App\Http\Controllers\SettingsController::class, 'toggleProfileLock'])->name('settings.toggle-profile-lock');
});

// Notifications routes - ensure they are accessible
Route::middleware('auth')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{notification}', [NotificationController::class, 'show'])->name('notifications.show');
    Route::post('/notifications/{notification}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-as-read');
    Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
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

Route::get('student_grades', [StudentGradeController::class, 'indexAll'])->name('student_grades.index')->middleware('auth');

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

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('activities', ActivityController::class)->only(['index', 'show', 'destroy']);
});

// Route pour afficher les détails d'une activité (pour le modal du dashboard)
Route::middleware(['auth'])->get('/activities/{id}', function($id) {
    $activity = Activity::with('user')->findOrFail($id);
    return view('dashboard.partials.activity_details', compact('activity'));
});

Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'fr'])) {
        session(['locale' => $locale]);
    }
    return back();
})->name('lang.switch');

// Two Factor Authentication
Route::get('/two-factor', [App\Http\Controllers\Auth\TwoFactorController::class, 'index'])->name('two-factor.index');
Route::post('/two-factor', [App\Http\Controllers\Auth\TwoFactorController::class, 'store'])->name('two-factor.verify');
Route::post('/two-factor/resend', [App\Http\Controllers\Auth\TwoFactorController::class, 'resend'])->name('two-factor.resend');

Route::resource('schools.hosts', App\Http\Controllers\SchoolHostController::class)->shallow()->only(['store']);
Route::delete('schools/{school}/hosts/{host}', [App\Http\Controllers\SchoolHostController::class, 'destroy'])->name('schools.hosts.destroy');

// Add route model binding for hosts as User
Route::bind('host', function ($value) {
    return \App\Models\User::findOrFail($value);
});

// API endpoint for classrooms by subject (for evaluation creation)
Route::get('/api/classrooms', [App\Http\Controllers\ClassRoomController::class, 'apiBySubject'])->middleware('auth');

// Routes AJAX pour la création dynamique du calendrier
Route::get('/api/teacher/{teacher}/classrooms', [App\Http\Controllers\CalendarController::class, 'getTeacherClassRooms']);
Route::get('/api/teacher/{teacher}/classroom/{classroom}/subjects', [App\Http\Controllers\CalendarController::class, 'getTeacherSubjectsForClassRoom']);

Route::get('/search', [\App\Http\Controllers\SearchController::class, 'index'])->name('search.global');

use App\Http\Controllers\UserController;

Route::middleware(['auth'])->group(function () {
    Route::resource('users', UserController::class);
    Route::post('users/{user}/validate', [UserController::class, 'validateUser'])->name('users.validate');
});

Route::get('/register/success', function () {
    return view('auth.register-success');
})->name('register.success');

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::get('/registration-success', function () {
    return view('auth.registration-success');
})->name('registration.success');

use App\Http\Controllers\VideoCallController;
use App\Http\Controllers\VideoCallMessageController;

Route::middleware(['auth'])->group(function () {
    // Video Calls Routes - History must come before resource
    Route::get('video-calls/history', [VideoCallController::class, 'history'])->name('video-calls.history');
    Route::resource('video-calls', VideoCallController::class);
    Route::get('video-calls/{roomId}/join', [VideoCallController::class, 'join'])->name('video-calls.join');
    Route::post('video-calls/{roomId}/leave', [VideoCallController::class, 'leave'])->name('video-calls.leave');
    Route::post('video-calls/{roomId}/decline', [VideoCallController::class, 'decline'])->name('video-calls.decline');
    Route::post('video-calls/{roomId}/end', [VideoCallController::class, 'end'])->name('video-calls.end');
    Route::post('video-calls/{roomId}/cancel', [VideoCallController::class, 'cancel'])->name('video-calls.cancel');
    Route::get('video-calls/{roomId}/participants', [VideoCallController::class, 'getActiveParticipants'])->name('video-calls.participants');
    Route::post('video-calls/{roomId}/status', [VideoCallController::class, 'updateParticipantStatus'])->name('video-calls.status');
    
    // Video Call Messages and Activities Routes
    Route::get('video-calls/{videoCall}/messages', [VideoCallMessageController::class, 'index'])->name('video-calls.messages.index');
    Route::post('video-calls/{videoCall}/messages', [VideoCallMessageController::class, 'store'])->name('video-calls.messages.store');
    Route::get('video-calls/{videoCall}/activities', [VideoCallMessageController::class, 'activities'])->name('video-calls.activities.index');
    Route::post('video-calls/{videoCall}/activities', [VideoCallMessageController::class, 'recordActivity'])->name('video-calls.activities.store');
});
