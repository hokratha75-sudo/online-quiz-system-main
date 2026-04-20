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

        $totalUsers = 0; $totalQuizzes = 0; $pendingReviews = 0; $totalDepartments = 0; 
        $newUsers = 0; $recentQuizzes = []; $departmentStats = []; $notifications = [];
        $myQuizzes = 0; $totalAttempts = 0; $avgScore = 0; $draftQuizzes = 0;
        $weeklyActivity = ['new_quizzes'=>0,'new_attempts'=>0]; $recentAttempts = []; $topQuizzes = []; $topPerformer = null;

        // For Student ONLY show relevant quizzes
        $studentQuizzes = [];

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
            $stats = Cache::remember('admin_dashboard_stats', 600, function() {
                return [
                    'totalUsers' => DB::table('users')->where('role_id', 3)->where('status', 'active')->count(),
                    'totalTeachers' => DB::table('users')->where('role_id', 2)->count(),
                    'totalQuizzes' => DB::table('quizzes')->count(),
                    'totalQuestions' => DB::table('questions')->count(),
                    'totalBank' => DB::table('questions')->where('is_reusable', true)->count(),
                    'pendingReviews' => DB::table('quizzes')->where('status', 'draft')->count(),
                    'totalDepartments' => DB::table('departments')->count(),
                    'newUsers' => DB::table('users')->where('created_at', '>=', now()->subDays(30))->count(),
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
                if($days->has($dateKey)) $days->get($dateKey)['quizzes'] = $q->count;
            }
            foreach($weeklyAttempts as $a) {
                $dateKey = date('M d', strtotime($a->date));
                if($days->has($dateKey)) $days->get($dateKey)['attempts'] = $a->count;
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
                if($days->has($dateKey)) $days->get($dateKey)['attempts'] = $a->count;
            }
            $weeklyActivity = ['labels' => $days->keys(), 'attempts' => $days->pluck('attempts')];

            $topPerformer = \App\Models\Result::with('user')
                ->join('attempts', 'results.attempt_id', '=', 'attempts.id')
                ->whereIn('attempts.quiz_id', $myQuizIds)
                ->orderBy('results.score', 'desc')->first();

        } elseif ($userRole === 'student') {
            $studentQuizQuery = \App\Models\Quiz::query()
                ->where('status', 'published')
                ->with('subject')
                ->withCount('questions');

            $studentQuizQuery->where(function($q) use ($user) {
                // 1. Check Department-based subjects (Master Enrollment)
                if ($user->department_id) {
                    $q->whereHas('subject', function ($sq) use ($user) {
                        $sq->where('department_id', $user->department_id);
                    });
                }
                
                // 2. Check Class-based subjects (Classic Enrollment)
                $q->orWhereHas('subject.classes.users', function($uq) use ($user) {
                    $uq->where('users.id', $user->id);
                });
            });

            $myQuizzes = (clone $studentQuizQuery)->count();
            $studentQuizzes = $studentQuizQuery->latest('created_at')->limit(12)->get();
            
            // Optimized: Consolidate student stats into one query
            $studentStats = DB::table('results')
                ->where('user_id', $user->id)
                ->selectRaw('AVG(score) as avg_score, COUNT(*) as total_attempts')
                ->first();

            $avgScore = round($studentStats->avg_score ?? 0, 1);
            $totalAttempts = $studentStats->total_attempts ?? 0;
            $recentAttempts = DB::table('results')
                ->join('quizzes', 'results.quiz_id', '=', 'quizzes.id')
                ->where('results.user_id', $user->id)
                ->select('results.*', 'quizzes.title as quiz_title')
                ->orderBy('results.completed_at', 'desc')
                ->limit(5)
                ->get()->map(function($r) { return (array) $r; })->toArray();

            // Weekly activity for Student (Their attempts)
            $weeklyAttempts = DB::table('attempts')
                ->where('user_id', $user->id)
                ->where('started_at', '>=', now()->subDays(7))
                ->select(DB::raw('DATE(started_at) as date'), DB::raw('COUNT(*) as count'))
                ->groupBy('date')->get();
            foreach($weeklyAttempts as $a) {
                $dateKey = date('M d', strtotime($a->date));
                if($days->has($dateKey)) $days->get($dateKey)['attempts'] = $a->count;
            }
        }

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
            'studentQuizzes', 'topPerformer'
        ));
    }
}
