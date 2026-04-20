<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Subject;
use App\Models\Department;
use App\Http\Requests\StoreQuizRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Traits\CSVExportTrait;

use App\Models\Result;
use App\Models\Attempt;

class QuizController extends Controller
{
    use CSVExportTrait;

    public function index(Request $request)
    {
        $user = Auth::user();
        $userRole = $user->role_id == 1 ? 'admin' : ($user->role_id == 2 ? 'teacher' : 'student');
        $dashboardTitle = 'All Quizzes';

        $query = Quiz::with(['subject.department'])->withCount('questions');

        // Teacher only sees their quizzes
        if ($userRole === 'teacher') {
            $query->where('created_by', $user->id);
        }

        // Apply Filters
        if ($request->filled('department_id')) {
            $query->whereHas('subject', function($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
        }

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $quizzes = $query->latest()->get();
        
        $departments = Department::all();
        $subjects = Subject::all();

        return view('admin.quizzes.index', compact('quizzes', 'userRole', 'dashboardTitle', 'departments', 'subjects'));
    }

    public function create()
    {
        $userRole = 'teacher';
        $dashboardTitle = 'Create Quiz';
        
        $user = Auth::user();
        // Since we are flattened, let's just get all subjects for now or those the teacher 'created'
        // or just all subjects if anyone can create quizzes for any subject.
        $subjects = Subject::all();

        return view('admin.quizzes.create', compact('subjects', 'userRole', 'dashboardTitle'));
    }

    public function store(StoreQuizRequest $request)
    {
        $quiz = Quiz::create([
            'title' => $request->title,
            'subject_id' => $request->subject_id,
            'status' => $request->status,
            'description' => $request->description,
            'time_limit' => $request->time_limit ?? 30,
            'shuffle_questions' => $request->boolean('shuffle_questions'),
            'pass_percentage' => $request->input('pass_percentage', 60),
            'created_by' => auth()->id(),
        ]);

        if ($quiz->status === 'published') {
            $students = \App\Models\User::where('role_id', 3)->get();
            \Illuminate\Support\Facades\Notification::send($students, new \App\Notifications\QuizActivityNotification([
                'type' => 'info',
                'title' => 'New Quiz Available',
                'message' => 'A new quiz "' . $quiz->title . '" has been published by your teacher.',
                'icon' => 'fas fa-book-open',
                'url' => route('students.dashboard') 
            ]));
        }

        return redirect()->route('quizzes.edit', $quiz->id)->with('success', 'Quiz created! You can now add questions.');
    }

    public function edit(Quiz $quiz)
    {
        if (!auth()->user()->isAdmin() && (int)$quiz->created_by !== (int)auth()->id()) {
            abort(403, 'Unauthorized to edit this quiz.');
        }

        $userRole = auth()->user()->isAdmin() ? 'admin' : (auth()->user()->isTeacher() ? 'teacher' : 'student');
        $dashboardTitle = 'Edit Quiz Builder';
        
        $quiz->load('questions.answers');
        $subjects = Subject::all();

        return view('admin.quizzes.edit', compact('quiz', 'subjects', 'userRole', 'dashboardTitle'));
    }

    public function update(StoreQuizRequest $request, Quiz $quiz)
    {
        if (!auth()->user()->isAdmin() && (int)$quiz->created_by !== (int)auth()->id()) {
            abort(403, 'Unauthorized to update this quiz.');
        }

        $wasPublished = $quiz->status === 'published';

        $quiz->update([
            'title' => $request->title,
            'subject_id' => $request->subject_id,
            'status' => $request->status,
            'description' => $request->description,
            'time_limit' => $request->time_limit ?? 30,
            'shuffle_questions' => $request->boolean('shuffle_questions'),
            'pass_percentage' => $request->input('pass_percentage', 60),
        ]);

        if (!$wasPublished && $quiz->status === 'published') {
            $students = \App\Models\User::where('role_id', 3)->get();
            \Illuminate\Support\Facades\Notification::send($students, new \App\Notifications\QuizActivityNotification([
                'type' => 'info',
                'title' => 'New Quiz Available',
                'message' => 'A new quiz "' . $quiz->title . '" has been published by your teacher.',
                'icon' => 'fas fa-book-open',
                'url' => route('students.dashboard') 
            ]));
        }

        return redirect()->route('quizzes.index')->with('success', 'Quiz updated successfully.');
    }

    public function show(Quiz $quiz)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $userRole = $user->isAdmin() ? 'admin' : ($user->isTeacher() ? 'teacher' : 'student');
        $dashboardTitle = $quiz->title;

        // Load attempts for admins/teachers, always load questions and answers for all roles
        if ($userRole !== 'student') {
            $quiz->load(['attempts.user', 'attempts.result']);
        }
        $quiz->load('questions.answers');

        // Load previous attempt data for students
        $previousAttempts = collect();
        if ($userRole === 'student') {
            $previousAttempts = \App\Models\Attempt::with('result')
                ->where('user_id', $user->id)
                ->where('quiz_id', $quiz->id)
                ->where('status', 'completed')
                ->latest('completed_at')
                ->get();
        }

        return view('admin.quizzes.show', compact('quiz', 'userRole', 'dashboardTitle', 'previousAttempts'));
    }

    public function take(Quiz $quiz)
    {
        if ($quiz->status !== 'published' && !auth()->user()->isAdmin()) {
            abort(403, 'This quiz is not published.');
        }

        $quiz->load('questions.answers');
        
        // Prevent duplicate fresh attempts on refresh
        $attempt = \App\Models\Attempt::firstOrCreate(
            [
                'user_id' => auth()->id(),
                'quiz_id' => $quiz->id,
                'status' => 'in_progress'
            ],
            [
                'started_at' => now(),
                'violations' => 0
            ]
        );

        // Architect Spec: Seeded Shuffle Logic (Step 2 - Full Option)
        if ($quiz->shuffle_questions) {
            $seed = (int)$attempt->id;
            // Shuffle questions based on seed
            $quiz->setRelation('questions', $quiz->questions->shuffle($seed));
            // Shuffle answers for each question based on same or related seed
            $quiz->questions->each(function($question) use ($seed) {
                // We add question id to seed so each question's answers shuffle differently but consistently
                $question->setRelation('answers', $question->answers->shuffle($seed + $question->id));
            });
        }

        $userRole = 'student';
        $dashboardTitle = "Taking: " . $quiz->title;

        return view('admin.quizzes.take', compact('quiz', 'userRole', 'dashboardTitle', 'attempt'));
    }

    public function submit(Request $request, Quiz $quiz)
    {
        $responses = $request->input('responses', []);
        $totalQuestions = $quiz->questions->count();
        $correctAnswers = 0;
        $needsManualGrading = false;
        
        $attempt = \App\Models\Attempt::where('user_id', auth()->id())
            ->where('quiz_id', $quiz->id)
            ->where('status', 'in_progress')
            ->orderBy('started_at', 'desc')
            ->first();

        if (!$attempt) {
            $fallback = auth()->user()->role_id == 3 ? route('students.dashboard') : route('quizzes.index');
            return redirect($fallback)->with('error', 'Could not process quiz submission. No active attempt found.');
        }

        $answerInsertData = [];
        $now = now();

        foreach ($quiz->questions as $question) {
            $submittedValue = $responses[$question->id] ?? null;
            $isCorrect = false;
            $answerId = null;
            $shortText = null;

            if ($question->type === 'short_answer') {
                $shortText = is_string($submittedValue) ? $submittedValue : null;
                $isCorrect = null; // Needs Review
                $needsManualGrading = true;
            } else {
                $answerId = is_numeric($submittedValue) ? $submittedValue : null;
                if ($answerId) {
                    $isCorrect = \App\Models\Answer::where('id', $answerId)
                        ->where('question_id', $question->id)
                        ->where('is_correct', true)
                        ->exists();
                    
                    if ($isCorrect) {
                        $correctAnswers++;
                    }
                }
            }

            // High Performance: Collect for bulk insert
            $answerInsertData[] = [
                'attempt_id' => $attempt->id,
                'question_id' => $question->id,
                'answer_id' => $answerId,
                'short_text' => $shortText,
                'is_correct' => $isCorrect,
                'points_awarded' => $isCorrect ? ($question->points ?? 1) : 0,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // Optimized: Single Bulk Insert for all answers
        if (!empty($answerInsertData)) {
            \App\Models\AttemptAnswer::insert($answerInsertData);
        }

        $score = $totalQuestions > 0 ? ($correctAnswers / $totalQuestions) * 100 : 0;
        $passed = $score >= ($quiz->pass_percentage ?? 60);

        $attempt->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        \App\Models\Result::create([
            'attempt_id' => $attempt->id,
            'user_id' => auth()->id(),
            'quiz_id' => $quiz->id,
            'score' => $score,
            'passed' => $passed,
            'started_at' => $attempt->started_at,
            'completed_at' => $attempt->completed_at ?? now(),
            'is_published' => !$needsManualGrading,
        ]);

        // --- Notify Teacher ---
        $teacher = \App\Models\User::find($quiz->created_by);
        if ($teacher) {
            $msg = $needsManualGrading 
                ? 'Student ' . auth()->user()->username . ' submitted "' . $quiz->title . '" and requires manual grading.'
                : 'Student ' . auth()->user()->username . ' just finished "' . $quiz->title . '" with a score of ' . round($score) . '%.';
            
            $teacher->notify(new \App\Notifications\QuizActivityNotification([
                'type' => $needsManualGrading ? 'warning' : 'success',
                'title' => 'New Submission',
                'message' => $msg,
                'icon' => $needsManualGrading ? 'fas fa-exclamation-circle' : 'fas fa-check-circle',
                'url' => route('quizzes.result', $attempt->id)
            ]));
        }

        $resultRoute = auth()->user()->role_id == 3
            ? route('students.quizzes.result', $attempt->id)
            : route('quizzes.result', $attempt->id);

        return redirect($resultRoute);
    }

    public function result(\App\Models\Attempt $attempt)
    {
        if ((int)$attempt->user_id !== (int)auth()->id() && !auth()->user()?->isAdmin() && !auth()->user()?->isTeacher()) {
            abort(403);
        }

        $attempt->load(['quiz.subject', 'result', 'quiz.questions.answers']);
        
        $attemptAnswers = \App\Models\AttemptAnswer::where('attempt_id', $attempt->id)
            ->get()->keyBy('question_id');

        $userRole = auth()->user()->role_id == 3 ? 'student' : (auth()->user()->role_id == 2 ? 'teacher' : 'admin');
        $dashboardTitle = "Quiz Attempt: " . $attempt->quiz->title;

        return view('admin.quizzes.result', compact('attempt', 'attemptAnswers', 'userRole', 'dashboardTitle'));
    }

    public function grade(Request $request, \App\Models\Attempt $attempt)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->isTeacher()) abort(403);

        $request->validate([
            'manual_score' => 'required|numeric|min:0|max:100',
            'teacher_feedback' => 'nullable|string',
            'publish' => 'nullable|boolean'
        ]);

        $passed = $request->manual_score >= ($attempt->quiz->pass_percentage ?? 60);

        $attempt->result()->update([
            'manual_score' => $request->manual_score,
            'is_published' => $request->has('publish'),
            'teacher_feedback' => $request->teacher_feedback,
            'passed' => $passed
        ]);

        // Specific answer feedbacks/points could be updated here if requested

        if ($request->has('publish')) {
            $student = \App\Models\User::find($attempt->user_id);
            if ($student) {
                $student->notify(new \App\Notifications\QuizActivityNotification([
                    'type' => 'info',
                    'title' => 'Grade Published',
                    'message' => 'Your teacher has published the final grade and feedback for "' . $attempt->quiz->title . '".',
                    'icon' => 'fas fa-clipboard-check',
                    'url' => route('students.quizzes.result', $attempt->id)
                ]));
            }
        }

        return redirect()->back()->with('success', 'Grades updated and saved successfully.');
    }

    public function recordViolation(Request $request, \App\Models\Attempt $attempt)
    {
        if ($attempt->user_id !== auth()->id() || $attempt->status !== 'in_progress') {
            return response()->json(['error' => 'Unauthorized or invalid state'], 403);
        }

        $attempt->increment('violations');
        $isDisqualified = $attempt->violations >= 3;

        // If disqualified, we don't automatically grade here; we let the frontend trigger the submit,
        // or we could force a grade. To keep it robust, the frontend will post the current answers.
        
        return response()->json([
            'success' => true,
            'violations' => $attempt->violations,
            'disqualified' => $isDisqualified
        ]);
    }

    /**
     * View Assessment Reports.
     */
    public function reports(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $query = \App\Models\Result::with(['user', 'quiz.subject.department']);

        // Scope to teacher if not admin
        if ($user->isTeacher()) {
            $query->whereHas('quiz', function($q) use ($user) {
                $q->where('created_by', $user->id);
            });
        }

        // Apply Filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($uq) use ($search) {
                    $uq->where('username', 'like', "%{$search}%");
                })->orWhereHas('quiz', function($qq) use ($search) {
                    $qq->where('title', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('department_id') && $user->isAdmin()) {
            $query->whereHas('quiz.subject', function($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
        }

        if ($request->filled('subject_id')) {
            $query->whereHas('quiz', function($q) use ($request) {
                $q->where('subject_id', $request->subject_id);
            });
        }

        if ($request->filled('teacher_id') && $user->isAdmin()) {
            $query->whereHas('quiz', function($q) use ($request) {
                $q->where('created_by', $request->teacher_id);
            });
        }

        $baseQuery = clone $query;

        // Export Handle
        if ($request->has('export') && $request->export === 'csv') {
            $exportData = $baseQuery->latest('completed_at')->get();
            $csvFileName = 'QuizMaster_Report_' . date('Y_m_d_His') . '.csv';
            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$csvFileName",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            ];
            $handle = fopen('php://temp', 'w');
            fputcsv($handle, ['Ref ID', 'Student', 'Quiz', 'Subject', 'Score (%)', 'Result', 'Date']);
            foreach ($exportData as $row) {
                $passStr = $row->score >= ($row->quiz->pass_percentage ?? 60) ? 'Passed' : 'Failed';
                fputcsv($handle, [
                    'A-' . str_pad($row->id, 5, '0', STR_PAD_LEFT),
                    $row->user->username ?? 'Unknown',
                    $row->quiz->title ?? 'N/A',
                    $row->quiz->subject->subject_name ?? 'N/A',
                    round($row->score, 1),
                    $passStr,
                    $row->completed_at ? $row->completed_at->format('Y-m-d H:i') : ''
                ]);
            }
            rewind($handle);
            $content = stream_get_contents($handle);
            fclose($handle);
            return response($content, 200, $headers);
        }

        // Dynamic Analytics
        $totalStudents = (clone $baseQuery)->distinct('user_id')->count('user_id');
        $avgScore = (clone $baseQuery)->avg('score') ?? 0;
        
        $totalAttempts = (clone $baseQuery)->count();
        $passedAttempts = (clone $baseQuery)->join('quizzes', 'results.quiz_id', '=', 'quizzes.id')
            ->whereRaw('results.score >= quizzes.pass_percentage')->count();
        $passRate = $totalAttempts > 0 ? round(($passedAttempts / $totalAttempts) * 100) : 0;
        
        $topPerformer = (clone $baseQuery)->orderBy('score', 'desc')->first();

        // Score Distribution
        $scoreDistribution = [
            'A' => (clone $baseQuery)->where('score', '>=', 90)->count(),
            'B' => (clone $baseQuery)->where('score', '>=', 80)->where('score', '<', 90)->count(),
            'C' => (clone $baseQuery)->where('score', '>=', 70)->where('score', '<', 80)->count(),
            'D' => (clone $baseQuery)->where('score', '>=', 60)->where('score', '<', 70)->count(),
            'E' => (clone $baseQuery)->where('score', '>=', 50)->where('score', '<', 60)->count(),
            'F' => (clone $baseQuery)->where('score', '<', 50)->count(),
        ];

        // Subject Performance Aggregate
        $subjectQ = DB::table('results')->join('quizzes', 'results.quiz_id', '=', 'quizzes.id')->join('subjects', 'quizzes.subject_id', '=', 'subjects.id');
        if ($user->isTeacher()) {
            $subjectQ->where('quizzes.created_by', $user->id);
        }
        $subjectPerformance = $subjectQ->select('subjects.subject_name', DB::raw('AVG(results.score) as average_score'))
            ->groupBy('subjects.id', 'subjects.subject_name')
            ->orderBy('average_score', 'desc')->take(5)->get();

        $results = $query->latest('completed_at')->paginate(10)->withQueryString();

        // Dropdowns for filters
        $departments = \App\Models\Department::orderBy('department_name')->get();
        $subjectsQuery = \App\Models\Subject::orderBy('subject_name');
        if ($user->isTeacher()) {
            // Teacher only sees subjects they teach/have quizzes in
            $subjectsQuery->whereHas('quizzes', function($q) use ($user) { $q->where('created_by', $user->id); });
        }
        $subjects = $subjectsQuery->get();
        $teachers = \App\Models\User::where('role_id', 2)->orderBy('username')->get();

        $userRole = $user->isAdmin() ? 'admin' : 'teacher';
        $dashboardTitle = 'Advanced Analytics & Reporting';

        return view('admin.quizzes.reports', compact(
            'results', 'totalStudents', 'avgScore', 'passRate', 
            'topPerformer', 'scoreDistribution', 'subjectPerformance',
            'userRole', 'dashboardTitle', 'departments', 'subjects', 'teachers'
        ));
    }

    public function bulkDelete(Request $request)
    {
        $request->validate(['ids' => 'required|array']);
        
        // Ownership check for bulk delete
        if (!auth()->user()?->isAdmin()) {
            \App\Models\Quiz::whereIn('id', $request->ids)->where('created_by', auth()->id())->delete();
        } else {
            \App\Models\Quiz::whereIn('id', $request->ids)->delete();
        }
        
        return redirect()->back()->with('success', 'Selected items processed successfully.');
    }

    public function destroy(Quiz $quiz)
    {
        if (!auth()->user()->isAdmin() && (int)$quiz->created_by !== (int)auth()->id()) {
            abort(403);
        }

        DB::beginTransaction();
        try {
            // Delete results linked to this quiz
            \App\Models\Result::where('quiz_id', $quiz->id)->delete();

            // Delete attempt_answers and attempts linked to this quiz
            $attemptIds = \App\Models\Attempt::where('quiz_id', $quiz->id)->pluck('id');
            if ($attemptIds->isNotEmpty()) {
                \App\Models\AttemptAnswer::whereIn('attempt_id', $attemptIds)->delete();
                \App\Models\Attempt::whereIn('id', $attemptIds)->delete();
            }

            // Delete answers linked to quiz questions, then questions
            $questionIds = $quiz->questions()->pluck('id');
            if ($questionIds->isNotEmpty()) {
                \App\Models\Answer::whereIn('question_id', $questionIds)->delete();
                $quiz->questions()->delete();
            }

            $quiz->delete();
            DB::commit();

            return redirect()->route('quizzes.index')->with('success', 'Quiz deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('quizzes.index')->with('error', 'Failed to delete quiz: ' . $e->getMessage());
        }
    }

    public function studentResults()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $allResults = \App\Models\Result::with(['quiz.subject', 'attempt'])
            ->where('user_id', $user->id)
            ->latest('completed_at')
            ->get();

        // === Analytics Computation ===
        $totalQuizzesTaken = $allResults->count();
        $totalPassed = $allResults->where('passed', true)->count();
        $totalFailed = $totalQuizzesTaken - $totalPassed;
        $avgScore = $totalQuizzesTaken > 0 ? round($allResults->avg('score'), 1) : 0;
        $highestScore = $totalQuizzesTaken > 0 ? round($allResults->max('score'), 1) : 0;
        $passRate = $totalQuizzesTaken > 0 ? round(($totalPassed / $totalQuizzesTaken) * 100) : 0;

        // Grade Distribution
        $gradeDistribution = [
            'A+' => $allResults->where('score', '>=', 95)->count(),
            'A'  => $allResults->where('score', '>=', 90)->where('score', '<', 95)->count(),
            'B+' => $allResults->where('score', '>=', 85)->where('score', '<', 90)->count(),
            'B'  => $allResults->where('score', '>=', 80)->where('score', '<', 85)->count(),
            'C+' => $allResults->where('score', '>=', 75)->where('score', '<', 80)->count(),
            'C'  => $allResults->where('score', '>=', 70)->where('score', '<', 75)->count(),
            'D'  => $allResults->where('score', '>=', 60)->where('score', '<', 70)->count(),
            'F'  => $allResults->where('score', '<', 60)->count(),
        ];

        // Per-Subject Performance
        $subjectPerformance = $allResults->groupBy(function($r) {
            return $r->quiz?->subject?->subject_name ?? 'General';
        })->map(function($group, $name) {
            return [
                'name' => $name,
                'count' => $group->count(),
                'avg' => round($group->avg('score'), 1),
                'best' => round($group->max('score'), 1),
            ];
        })->sortByDesc('avg')->values()->toArray();

        // Score Trend (last 10 attempts chronologically)
        $scoreTrend = $allResults->sortBy('completed_at')->take(10)->map(function($r) {
            return [
                'label' => $r->quiz?->title ? \Illuminate\Support\Str::limit($r->quiz->title, 12) : 'Quiz',
                'score' => round($r->score, 1),
                'date' => $r->completed_at ? $r->completed_at->format('M d') : '',
            ];
        })->values()->toArray();

        // Current Streak (consecutive passes, newest first)
        $streak = 0;
        foreach ($allResults as $r) {
            if ($r->passed) { $streak++; } else { break; }
        }

        // Violations total
        $totalViolations = \App\Models\Attempt::where('user_id', $user->id)->sum('violations');

        // Paginated results for the table
        $results = \App\Models\Result::with(['quiz.subject', 'attempt'])
            ->where('user_id', $user->id)
            ->latest('completed_at')
            ->paginate(10);

        $userRole = 'student';
        $dashboardTitle = 'My Performance Hub';

        return view('admin.quizzes.student_results', compact(
            'results', 'userRole', 'dashboardTitle',
            'totalQuizzesTaken', 'totalPassed', 'totalFailed', 'avgScore', 'highestScore', 'passRate',
            'gradeDistribution', 'subjectPerformance', 'scoreTrend', 'streak', 'totalViolations'
        ));
    }

    public function export()
    {
        $headers = ['#', 'Code', 'Quiz Title', 'Subject', 'Department', 'Total Questions', 'Status'];
        $quizzes = Quiz::with(['subject.department'])->withCount('questions')->get();
        
        $data = [];
        foreach ($quizzes as $index => $quiz) {
            $data[] = [
                $index + 1,
                'QZ' . str_pad($quiz->id, 4, '0', STR_PAD_LEFT),
                $quiz->title,
                $quiz->subject->subject_name ?? 'N/A',
                $quiz->subject->department->department_name ?? 'Ungrouped',
                $quiz->questions_count,
                ucfirst($quiz->status)
            ];
        }

        return $this->exportCSV($headers, $data, 'quizzes_data.csv');
    }
}

