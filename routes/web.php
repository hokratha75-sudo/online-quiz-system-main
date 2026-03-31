<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\MajorController;
use App\Http\Controllers\Admin\ClassModelController;
use App\Http\Controllers\Admin\ClassEnrollmentController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\SettingsController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('migrate', function () {
    Artisan::call('migrate --force');
    return 'Database migrated successfully. <a href="/seed">Click here to seed (may take time)</a>';
});

Route::get('seed', function () {
    Artisan::call('db:seed --class=AcademicStructureSeeder --force');
    return Artisan::output() ?: 'Seeding completed successfully!';
});

Route::get('link-storage', function () {
    try {
        Artisan::call('storage:link');
        return 'Storage link created successfully!';
    } catch (\Exception $e) {
        return 'Storage link failed: ' . $e->getMessage();
    }
});

// Auth Routes
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Authenticated Routes
Route::middleware(['auth'])->group(function () {

    // --- Common Profile Routes ---
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::get('/courses', [SubjectController::class, 'myCourses'])->name('courses.index');
    Route::get('/courses/{subject}', [SubjectController::class, 'showCourse'])->name('courses.show');
    Route::post('/courses/{subject}/materials', [\App\Http\Controllers\MaterialController::class, 'store'])->name('materials.store');
    Route::delete('/materials/{material}', [\App\Http\Controllers\MaterialController::class, 'destroy'])->name('materials.destroy');

    // --- ADMIN ONLY (Role 1) ---
    Route::middleware(['role:1'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('users', UserController::class)->except(['show']);
        Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::post('settings', [SettingsController::class, 'update'])->name('settings.update');
        
        // Hierarchy & Academic Management
        Route::get('departments/export', [DepartmentController::class, 'export'])->name('departments.export');
        Route::post('departments/bulk-delete', [DepartmentController::class, 'bulkDelete'])->name('departments.bulkDelete');
        Route::resource('departments', DepartmentController::class)->except(['create', 'edit']);

        Route::get('majors/export', [MajorController::class, 'export'])->name('majors.export');
        Route::post('majors/bulk-delete', [MajorController::class, 'bulkDelete'])->name('majors.bulkDelete');
        Route::get('majors/hierarchy', [MajorController::class, 'hierarchy'])->name('majors.hierarchy');
        Route::resource('majors', MajorController::class)->except(['create', 'edit']);
        
        Route::post('classes/bulk-delete', [ClassModelController::class, 'bulkDelete'])->name('classes.bulkDelete');
        Route::resource('classes', ClassModelController::class)->except(['create', 'edit']);

        Route::get('subjects/export', [SubjectController::class, 'export'])->name('subjects.export');
        Route::post('subjects/bulk-delete', [SubjectController::class, 'bulkDelete'])->name('subjects.bulkDelete');
        Route::resource('subjects', SubjectController::class)->except(['create', 'edit']);
        
        // Enrollments
        Route::get('enrollments', [ClassEnrollmentController::class, 'index'])->name('enrollments.index');
        Route::get('enrollments/{department}/manage', [ClassEnrollmentController::class, 'manage'])->name('enrollments.manage');
        Route::put('enrollments/{department}', [ClassEnrollmentController::class, 'update'])->name('enrollments.update');
    });

    // --- TEACHER & ADMIN (Role 1, 2) ---
    Route::middleware(['role:1,2'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('quizzes/export', [QuizController::class, 'export'])->name('quizzes.export');
        Route::post('quizzes/bulk-delete', [QuizController::class, 'bulkDelete'])->name('quizzes.bulkDelete');
        Route::get('quizzes/{attempt}/result', [QuizController::class, 'result'])->name('quizzes.result');
        Route::post('quizzes/{attempt}/grade', [QuizController::class, 'grade'])->name('quizzes.grade');
        Route::get('quizzes/{quiz}/take', [QuizController::class, 'take'])->name('quizzes.take');
        Route::post('quizzes/{quiz}/submit', [QuizController::class, 'submit'])->name('quizzes.submit');
        Route::post('quizzes/attempts/{attempt}/violation', [QuizController::class, 'recordViolation'])->name('quizzes.violation');
        Route::resource('quizzes', QuizController::class);
        Route::get('/reports', [QuizController::class, 'reports'])->name('quizzes.reports');
        Route::get('/question-bank', [QuestionController::class, 'bank'])->name('questions.bank');
        Route::post('/questions', [QuestionController::class, 'store'])->name('questions.store');
        Route::delete('/questions/{question}', [QuestionController::class, 'destroy'])->name('questions.destroy');
    });

    // --- STUDENT ONLY (Role 3) ---
    Route::middleware(['role:3'])->prefix('student')->name('students.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/results', [QuizController::class, 'studentResults'])->name('results');
        Route::get('/quizzes/{quiz}', [QuizController::class, 'show'])->name('quizzes.show');
        Route::get('/quizzes/{quiz}/take', [QuizController::class, 'take'])->name('quizzes.take');
        Route::post('/quizzes/{quiz}/submit', [QuizController::class, 'submit'])->name('quizzes.submit');
        Route::post('/quizzes/attempts/{attempt}/violation', [QuizController::class, 'recordViolation'])->name('quizzes.violation');
        Route::get('/quizzes/result/{attempt}', [QuizController::class, 'result'])->name('quizzes.result');
    });

    // Notifications Mark as Read
    Route::post('/notifications/mark-as-read/{id}', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/mark-all-read', function() {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    })->name('notifications.markAllRead');

});
