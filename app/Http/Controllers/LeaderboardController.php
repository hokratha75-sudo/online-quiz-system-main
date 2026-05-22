<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeaderboardController extends Controller
{
    public function leaderboard(Request $request)
    {
        // Subject list for filter dropdown
        $filterSubjects = Subject::orderBy('subject_name')->get();

    $subjectId = $request->subject_id;

    if ($subjectId) {

        $subject = Subject::find($subjectId);

        $rankings = Result::query()
            ->join('users', 'results.user_id', '=', 'users.id')
            ->join('quizzes', 'results.quiz_id', '=', 'quizzes.id')
            ->where('quizzes.subject_id', $subjectId)
            ->select(
                'users.id',
                'users.username',
                'users.profile_photo',
                DB::raw('AVG(results.score) as avg_score'),
                DB::raw('COUNT(results.id) as quizzes_taken'),
                DB::raw('
                    (
                        SUM(CASE WHEN results.score >= 50 THEN 1 ELSE 0 END)
                        / COUNT(results.id)
                    ) * 100 as pass_rate
                ')
            )
            ->groupBy('users.id', 'users.username', 'users.profile_photo')
            ->orderByDesc('avg_score')
            ->paginate(10)
            ->withQueryString();

        return view('leaderboard', [
            'rankings' => $rankings,
            'filterSubjects' => $filterSubjects,
        ]);
    }

    // Overall rankings
    $rankings = Result::query()
        ->join('users', 'results.user_id', '=', 'users.id')
        ->select(
            'users.id',
            'users.username',
            'users.profile_photo',
            DB::raw('AVG(results.score) as avg_score'),
            DB::raw('COUNT(results.id) as quizzes_taken'),
            DB::raw('
                (
                    SUM(CASE WHEN results.score >= 50 THEN 1 ELSE 0 END)
                    / COUNT(results.id)
                ) * 100 as pass_rate
            ')
        )
        ->groupBy('users.id', 'users.username', 'users.profile_photo')
        ->orderByDesc('avg_score')
        ->paginate(10)
        ->withQueryString();

    return view('leaderboard', [
        'rankings' => $rankings,
        'filterSubjects' => $filterSubjects,
    ]);
    }
}
