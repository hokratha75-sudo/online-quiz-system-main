<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user) return redirect('/login');
        
        $userRole = 'student';
        if ($user->role_id == 1) $userRole = 'admin';
        elseif ($user->role_id == 2) $userRole = 'teacher';

        $username = $user->username;
        $dashboardTitle = ucfirst($userRole) . ' Dashboard';

        // Initialize default variables
        $totalUsers = 0; $totalTeachers = 0; $totalQuizzes = 0; $pendingReviews = 0; $totalDepartments = 0; 
        $newUsers = 0; $recentQuizzes = []; $departmentStats = []; $notifications = [];
        $myQuizzes = 0; $totalAttempts = 0; $avgScore = 0; $draftQuizzes = 0;
        $weeklyActivity = ['labels' => [], 'quizzes' => [], 'attempts' => []];
        $recentAttempts = []; $topQuizzes = []; $topPerformer = null;
        $studentGenderStats = ['Male' => 0, 'Female' => 0];
        
        // Student specific variables
        $availableQuizzes = [];
        $quizHistory = [];
        $totalPassed = 0;
        $highestScore = 0;
        $streak = 0;

        $totalQuestions = 0; $totalBank = 0;

        // --- REAL WEEKLY ACTIVITY DATA ---
        $days = collect();
        for ($i = 6; $i >= 0; $i--) {
            $days->put(now()->subDays($i)->format('M d'), [
                'quizzes' => 0,
                'attempts' => 0
            ]);
        }

        if ($userRole === 'admin') {
            // Force fresh stats for gender to avoid cache lag
            Cache::forget('admin_dashboard_stats');
            
            $stats = Cache::remember('admin_dashboard_stats', 600, function() {
                $genderData = DB::table('users')
                    ->where('role_id', 3)
                    ->select(DB::raw('LOWER(sex) as gender'), DB::raw('count(*) as count'))
                    ->groupBy('gender')
                    ->get();

                $mappedStats = ['Male' => 0, 'Female' => 0];
                foreach($genderData as $gd) {
                    if($gd->gender == 'male') $mappedStats['Male'] = $gd->count;
                    if($gd->gender == 'female') $mappedStats['Female'] = $gd->count;
                }

                return [
                    'totalUsers' => DB::table('users')->where('role_id', 3)->where('status', 'active')->count(),
                    'totalTeachers' => DB::table('users')->where('role_id', 2)->count(),
                    'totalQuizzes' => DB::table('quizzes')->count(),
                    'totalQuestions' => DB::table('questions')->count(),
                    'totalBank' => DB::table('questions')->where('is_reusable', true)->count(),
                    'pendingReviews' => DB::table('quizzes')->where('status', 'draft')->count(),
                    'totalDepartments' => DB::table('departments')->count(),
                    'newUsers' => DB::table('users')->where('created_at', '>=', now()->subDays(30))->count(),
                    'studentGenderStats' => $mappedStats
                ];
            });

            extract($stats);
            
            $recentQuizzes = DB::table('quizzes')
                ->join('users', 'quizzes.created_by', '=', 'users.id')
                ->select('quizzes.*', 'users.username as creator_name')
                ->orderBy('quizzes.created_at', 'desc')
                ->limit(6)
                ->get()->map(function($q) { return (array) $q; })->toArray();

            $departmentStats = DB::table('departments')
                ->leftJoin('users', 'departments.id', '=', 'users.department_id')
                ->whereNull('departments.deleted_at')
                ->select('departments.department_name as name', DB::raw('COUNT(users.id) as user_count'))
                ->groupBy('departments.id', 'departments.department_name')
                ->orderBy('departments.department_name')
                ->get()->map(function($d) { return (array) $d; })->toArray();

            // Weekly performance for Admin (Global)
            $weeklyQuizzes = DB::table('quizzes')
                ->where('created_at', '>=', now()->subDays(7))
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
                ->groupBy('date')->get();
            $weeklyAttempts = DB::table('attempts')
                ->where('started_at', '>=', now()->subDays(7))
                ->select(DB::raw('DATE(started_at) as date'), DB::raw('COUNT(*) as count'))
                ->groupBy('date')->get();

            foreach($weeklyQuizzes as $q) {
                $dateKey = date('M d', strtotime($q->date));
                if($days->has($dateKey)) {
                    $item = $days->get($dateKey);
                    $item['quizzes'] = $q->count;
                    $days->put($dateKey, $item);
                }
            }
            foreach($weeklyAttempts as $a) {
                $dateKey = date('M d', strtotime($a->date));
                if($days->has($dateKey)) {
                    $item = $days->get($dateKey);
                    $item['attempts'] = $a->count;
                    $days->put($dateKey, $item);
                }
            }

        } elseif ($userRole === 'teacher') {
            // Optimized: Cache teacher-specific statistics for 5 minutes (300s)
            $teacherStats = Cache::remember("teacher_stats_{$user->id}", 300, function() use ($user) {
                $myQuizzesCount = DB::table('quizzes')->where('created_by', $user->id)->count();
                $draftQuizzesCount = DB::table('quizzes')->where('created_by', $user->id)->where('status', 'draft')->count();
                $myQuizIds = DB::table('quizzes')->where('created_by', $user->id)->pluck('id');
                $totalAttemptsCount = DB::table('attempts')->whereIn('quiz_id', $myQuizIds)->count();
                
                $avgScoreData = DB::table('results')
                    ->join('attempts', 'results.attempt_id', '=', 'attempts.id')
                    ->whereIn('attempts.quiz_id', $myQuizIds)
                    ->avg('score');

                return [
                    'myQuizzes' => $myQuizzesCount,
                    'draftQuizzes' => $draftQuizzesCount,
                    'totalAttempts' => $totalAttemptsCount,
                    'avgScore' => round($avgScoreData ?? 0, 1),
                    'myQuizIds' => $myQuizIds
                ];
            });

            extract($teacherStats);

            $recentAttempts = DB::table('attempts')
                ->join('quizzes', 'attempts.quiz_id', '=', 'quizzes.id')
                ->join('users', 'attempts.user_id', '=', 'users.id')
                ->leftJoin('results', 'attempts.id', '=', 'results.attempt_id')
                ->where('quizzes.created_by', $user->id)
                ->select('attempts.*', 'quizzes.title as quiz_title', 'users.username as student_name', 'results.score', 'results.passed')
                ->orderBy('attempts.started_at', 'desc')
                ->limit(6)
                ->get()->map(function($a) { return (array) $a; })->toArray();

            // Weekly activity for Teacher (Their quizzes)
            $weeklyAttempts = DB::table('attempts')
                ->whereIn('quiz_id', $myQuizIds)
                ->where('started_at', '>=', now()->subDays(7))
                ->select(DB::raw('DATE(started_at) as date'), DB::raw('COUNT(*) as count'))
                ->groupBy('date')->get();
            foreach($weeklyAttempts as $a) {
                $dateKey = date('M d', strtotime($a->date));
                if($days->has($dateKey)) {
                    $item = $days->get($dateKey);
                    $item['attempts'] = $a->count;
                    $days->put($dateKey, $item);
                }
            }

            $topPerformer = \App\Models\Result::with('user')
                ->join('attempts', 'results.attempt_id', '=', 'attempts.id')
                ->whereIn('attempts.quiz_id', $myQuizIds)
                ->orderBy('results.score', 'desc')->first();

        } elseif ($userRole === 'student') {
            // ============================================
            // FIXED: Separate AVAILABLE QUIZZES from HISTORY
            // ============================================
            
            $studentId = $user->id;
            
            // 1. Get quiz IDs that the student has ALREADY completed (has a result)
            $completedQuizIds = DB::table('results')
                ->where('user_id', $studentId)
                ->whereNotNull('completed_at')
                ->pluck('quiz_id')
                ->unique()
                ->toArray();
            
            // 2. Get AVAILABLE QUIZZES (published, not completed by student)
            $availableQuizzesQuery = \App\Models\Quiz::query()
                ->where('status', 'published')
                ->whereNotIn('id', $completedQuizIds)
                ->with(['subject'])
                ->withCount('questions');
            
            // Filter by department/class access
            $availableQuizzesQuery->where(function($q) use ($user) {
                // Department-based subjects
                if ($user->department_id) {
                    $q->whereHas('subject', function ($sq) use ($user) {
                        $sq->where('department_id', $user->department_id);
                    });
                }
                
                // Class-based enrollment
                $q->orWhereHas('subject.classes.users', function($uq) use ($user) {
                    $uq->where('users.id', $user->id);
                });
            });
            
            $availableQuizzes = $availableQuizzesQuery
                ->orderBy('created_at', 'desc')
                ->limit(20)
                ->get();
            
            // Add attempt count for each available quiz (if retakes allowed)
            foreach ($availableQuizzes as $quiz) {
                $quiz->attempts_count = DB::table('attempts')
                    ->where('quiz_id', $quiz->id)
                    ->where('user_id', $studentId)
                    ->count();
            }
            
            // 3. Get QUIZ HISTORY (completed attempts with results)
            $quizHistory = \App\Models\Result::where('user_id', $studentId)
                ->whereNotNull('completed_at')
                ->with(['quiz.subject'])
                ->orderBy('completed_at', 'desc')
                ->get();
            
            // 4. Calculate Student Statistics
            $totalAttempts = $quizHistory->count();
            $totalPassed = $quizHistory->where('passed', true)->count();
            $avgScore = round($quizHistory->avg('score') ?? 0, 1);
            $highestScore = round($quizHistory->max('score') ?? 0);
            
            // 5. Calculate Streak (consecutive days with quiz completions)
            $completedDates = $quizHistory->pluck('completed_at')
                ->filter()
                ->map(fn($date) => \Carbon\Carbon::parse($date)->toDateString())
                ->unique()
                ->sortDesc()
                ->values();
            
            $streak = 0;
            $expectedDate = \Carbon\Carbon::today();
            foreach ($completedDates as $date) {
                if (\Carbon\Carbon::parse($date)->toDateString() === $expectedDate->toDateString()) {
                    $streak++;
                    $expectedDate->subDay();
                } elseif (\Carbon\Carbon::parse($date)->toDateString() === $expectedDate->addDay()->toDateString()) {
                    // Continue streak
                    continue;
                } else {
                    break;
                }
            }
            
            // 6. Recent attempts for quick view
            $recentAttempts = $quizHistory->take(5)->map(function($result) {
                return [
                    'id' => $result->id,
                    'quiz_title' => $result->quiz?->title ?? 'Unknown',
                    'score' => $result->score,
                    'passed' => $result->passed,
                    'completed_at' => $result->completed_at,
                ];
            })->toArray();
            
            // 7. My Quizzes count (total quizzes student has access to)
            $myQuizzes = $availableQuizzesQuery->count();
            
            // 8. Weekly activity for Student (Their attempts)
            $weeklyAttempts = DB::table('attempts')
                ->where('user_id', $studentId)
                ->where('started_at', '>=', now()->subDays(7))
                ->select(DB::raw('DATE(started_at) as date'), DB::raw('COUNT(*) as count'))
                ->groupBy('date')->get();
                
            foreach($weeklyAttempts as $a) {
                $dateKey = date('M d', strtotime($a->date));
                if($days->has($dateKey)) {
                    $item = $days->get($dateKey);
                    $item['attempts'] = $a->count;
                    $days->put($dateKey, $item);
                }
            }
        }

        // Build weekly activity array for all roles
        $weeklyActivity = [
            'labels' => $days->keys(),
            'quizzes' => $days->pluck('quizzes'),
            'attempts' => $days->pluck('attempts')
        ];

        $totalTeachers = $totalTeachers ?? 0;

        return view('admin.dashboard', compact(
            'userRole', 'username', 'dashboardTitle',
            'totalUsers', 'totalTeachers', 'totalQuizzes', 'totalQuestions', 'totalBank', 'pendingReviews', 'totalDepartments', 'newUsers',
            'recentQuizzes', 'departmentStats', 'notifications',
            'myQuizzes', 'totalAttempts', 'avgScore', 'draftQuizzes', 'weeklyActivity', 'recentAttempts', 'topQuizzes',
            'availableQuizzes', 'quizHistory', 'topPerformer', 'studentGenderStats',
            'totalPassed', 'highestScore', 'streak'  // Student-specific stats
        ));
    }
}