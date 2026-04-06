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
    public function bank()
    {
        $questions = Question::reusable()->with(['quiz.subject'])->latest()->get();
        $userRole = auth()->user()?->isAdmin() ? 'admin' : 'teacher';
        $dashboardTitle = 'Global Question Bank';

        return view('admin.questions.bank', compact('questions', 'userRole', 'dashboardTitle'));
    }
}
