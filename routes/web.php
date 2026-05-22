<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

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
use App\Http\Controllers\Admin\StudentReportController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| HOME
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| SYSTEM HELP (OPTIONAL)
|--------------------------------------------------------------------------
*/

Route::get('/fix-routes', function () {
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('cache:clear');
    return 'Caches cleared';
});

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/

Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->name('logout');
});

Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', 'register');
});

Route::controller(ForgotPasswordController::class)->group(function () {
    Route::get('/forgot-password', 'showLinkRequestForm')->name('password.request');
    Route::post('/forgot-password', 'sendResetLinkEmail')->name('password.email');
});

Route::controller(ResetPasswordController::class)->group(function () {
    Route::get('/reset-password/{token}', 'showResetForm')->name('password.reset');
    Route::post('/reset-password', 'reset')->name('password.update');
});

/*
|--------------------------------------------------------------------------
| AUTH MIDDLEWARE
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD REDIRECT
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', function () {
        $user = Auth::user();

        return match ((int)$user->role_id) {
            1 => redirect()->route('admin.dashboard'),
            2 => redirect()->route('teacher.dashboard'),
            3 => redirect()->route('students.dashboard'),
            default => abort(403),
        };
    })->name('dashboard');
    Route::get('/leaderboard', [QuizController::class, 'leaderboard'])
    ->name('leaderboard');
    /*
    |--------------------------------------------------------------------------
    | PROFILE
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    /*
    |--------------------------------------------------------------------------
    | 🔥 FIXED: COURSES ROUTES (THIS FIXES YOUR ERROR)
    |--------------------------------------------------------------------------
    */
    Route::get('/courses', [SubjectController::class, 'myCourses'])
        ->name('courses.index');

    Route::get('/courses/{subject}', [SubjectController::class, 'showCourse'])
        ->name('courses.show');

    /*
    |--------------------------------------------------------------------------
    | ADMIN (ROLE 1)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:1'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {

            Route::get('/dashboard', [DashboardController::class, 'index'])
                ->name('dashboard');

            Route::resource('users', UserController::class)->except(['show']);
            Route::resource('departments', DepartmentController::class)->except(['create','edit']);
            Route::resource('majors', MajorController::class)->except(['create','edit']);
            Route::resource('classes', ClassModelController::class)->except(['create','edit']);
            Route::resource('subjects', SubjectController::class)->except(['create','edit']);
            Route::resource('quizzes', QuizController::class);

            Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
            Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
        });

    /*
    |--------------------------------------------------------------------------
    | TEACHER (ROLE 2)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:2'])
        ->prefix('teacher')
        ->name('teacher.')
        ->group(function () {

            Route::get('/dashboard', [DashboardController::class, 'index'])
                ->name('dashboard');

            Route::resource('quizzes', QuizController::class);

            Route::get('/reports', [QuizController::class, 'reports'])
                ->name('quizzes.reports');

            Route::post('/questions', [QuestionController::class, 'store'])
                ->name('questions.store');

            Route::put('/questions/{question}', [QuestionController::class, 'update'])
                ->name('questions.update');

            Route::delete('/questions/{question}', [QuestionController::class, 'destroy'])
                ->name('questions.destroy');
        });

    /*
    |--------------------------------------------------------------------------
    | STUDENT (ROLE 3)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:3'])
        ->prefix('student')
        ->name('students.')
        ->group(function () {

            Route::get('/dashboard', [DashboardController::class, 'index'])
                ->name('dashboard');

            Route::get('/results', [QuizController::class, 'studentResults'])
                ->name('results');

            Route::get('/quizzes/{quiz}', [QuizController::class, 'show'])
                ->name('quizzes.show');

            Route::get('/quizzes/{quiz}/take', [QuizController::class, 'take'])
                ->name('quizzes.take');

            Route::get('/quizzes/result/{attempt}', [QuizController::class, 'result'])
                ->name('quizzes.result');

            Route::post('/quizzes/{quiz}/submit', [QuizController::class, 'submit'])
                ->name('quizzes.submit');
        });
});