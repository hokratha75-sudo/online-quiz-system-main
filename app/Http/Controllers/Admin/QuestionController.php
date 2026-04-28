<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Answer;
use App\Http\Requests\StoreQuestionRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QuestionController extends Controller
{
    /**
     * Store a new question securely.
     * Follows OWASP: Validation (Request Class), Authorization (authorize method), XSS (stripping tags in Request).
     */
    public function store(StoreQuestionRequest $request)
    {
        $validated = $request->validated();

        try {
            DB::beginTransaction();

            $content = $validated['content'];
            // Security: Ensure we are only saving the text, if it's JSON from a previous request, sanitize it.
            if (is_string($content) && str_starts_with($content, '{')) {
                $decoded = json_decode($content, true);
                if (is_array($decoded) && isset($decoded['content'])) {
                    $content = $decoded['content'];
                }
            }

            $question = Question::create([
                'quiz_id' => $validated['quiz_id'],
                'content' => $content,
                'type' => $validated['type'],
                'points' => $validated['points'],
                'is_reusable' => $validated['is_reusable'] ?? false,
            ]);

            if ($validated['type'] !== 'short_answer') {
                foreach ($validated['options'] as $index => $optionText) {
                    Answer::create([
                        'question_id' => $question->id,
                        'answer_text' => $optionText,
                        'is_correct' => in_array($index, $validated['correct']),
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Question added successfully.',
                'question' => $question
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Question creation failed: ' . $e->getMessage(), ['user_id' => auth()->id()]);
            
            // OWASP: Do not leak exception message to the user
            return response()->json([
                'success' => false,
                'message' => 'A system error occurred while adding the question.'
            ], 500);
        }
    }

    /**
     * Delete a question securely.
     */
    public function destroy(Question $question)
    {
        // OWASP: Authorization check for deletion
        if (auth()->user()?->isAdmin() || (int)$question->quiz->created_by === (int)auth()->id()) {
            $question->delete();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
    }

    /**
     * View the Global Question Bank.
     */
    public function bank(\Illuminate\Http\Request $request)
    {
        $query = Question::reusable()->with(['quiz.subject']);
        
        // --- Apply Filters ---
        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }
        if ($request->filled('subject_id')) {
            $query->whereHas('quiz', function($q) use ($request) {
                $q->where('subject_id', $request->subject_id);
            });
        }
        if ($request->filled('search')) {
            $query->where('content', 'LIKE', '%' . $request->search . '%');
        }

        // Calculate Global Bank Statistics
        $stats = [
            'total' => Question::reusable()->count(),
            'mc' => Question::reusable()->where('type', 'multiple_choice')->count(),
            'sc' => Question::reusable()->where('type', 'single_choice')->count(),
            'tf' => Question::reusable()->where('type', 'true_false')->count(),
        ];

        $questions = $query->latest()->paginate(15)->appends($request->all());
        $subjects = \App\Models\Subject::orderBy('subject_name')->get();
        $userRole = auth()->user()?->isAdmin() ? 'admin' : 'teacher';
        $dashboardTitle = 'Global Question Bank';

        return view('admin.questions.bank', compact('questions', 'userRole', 'dashboardTitle', 'stats', 'subjects'));
    }

    /**
     * Export Question Bank to CSV.
     */
    public function export()
    {
        $headers = ['ID', 'Content', 'Type', 'Quiz', 'Subject', 'Points', 'Date Added'];
        $questions = Question::reusable()->with(['quiz.subject'])->get();
        
        $data = [];
        foreach ($questions as $q) {
            $data[] = [
                $q->id,
                strip_tags($q->content),
                ucfirst(str_replace('_', ' ', $q->type)),
                $q->quiz->title ?? 'N/A',
                $q->quiz->subject->subject_name ?? 'N/A',
                $q->points,
                $q->created_at->format('Y-m-d')
            ];
        }

        $filename = 'question_bank_export_' . date('Y-m-d') . '.csv';
        
        $callback = function() use ($headers, $data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $headers);
            foreach ($data as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ]);
    }

    /**
     * Bulk Delete questions securely.
     */
    public function bulkDelete(\Illuminate\Http\Request $request)
    {
        $request->validate(['ids' => 'required|array']);
        
        // OWASP: Verify authorization for each question before deleting
        $deletedCount = 0;
        foreach ($request->ids as $id) {
            $question = Question::find($id);
            if ($question && (auth()->user()?->isAdmin() || (int)$question->quiz->created_by === (int)auth()->id())) {
                $question->delete();
                $deletedCount++;
            }
        }

        return redirect()->back()->with('success', "Successfully removed $deletedCount questions from the bank.");
    }
}
