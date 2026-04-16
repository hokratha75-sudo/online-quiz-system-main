<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Answer;
use Illuminate\Support\Facades\Route;

class QuizShowTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function student_can_view_quiz_with_questions()
    {
        // Create a student user
        $student = User::factory()->create(['role_id' => 3]);

        // Create a quiz with a question and answers
        $quiz = Quiz::factory()->create();
        $question = Question::factory()->create([
            'quiz_id' => $quiz->id,
            'type' => 'single_choice',
            'content' => 'What is 2+2?',
        ]);
        Answer::factory()->create([
            'question_id' => $question->id,
            'answer_text' => '4',
            'is_correct' => true,
        ]);
        Answer::factory()->create([
            'question_id' => $question->id,
            'answer_text' => '5',
            'is_correct' => false,
        ]);

        // Act as student and visit the quiz show route
        $response = $this->actingAs($student)->get(route('students.quizzes.show', $quiz->id));

        // Assert the view loads and contains the question content
        $response->assertStatus(200);
        $response->assertSee('What is 2+2?');
        $response->assertSee('4');
        $response->assertSee('5');
    }
}
?>
