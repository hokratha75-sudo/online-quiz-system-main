<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiQuestionController extends Controller
{
    public function generate(Request $request, Quiz $quiz)
    {
        $request->validate([
            'question_count' => 'required|integer|min:1|max:20',
            'material_text' => 'nullable|string',
            'material_file' => 'nullable|file|mimes:pdf,txt'
        ]);

        if (!$request->filled('material_text') && !$request->hasFile('material_file')) {
            return back()->with('error', 'Please provide either text material or upload a file.');
        }

        $text = $request->input('material_text');

        if ($request->hasFile('material_file')) {
            $file = $request->file('material_file');
            
            if ($file->getClientOriginalExtension() === 'pdf') {
                try {
                    if (!class_exists('\Smalot\PdfParser\Parser')) {
                        return back()->with('error', 'PDF Parsing library is missing. Please run: composer require smalot/pdfparser');
                    }
                    $parser = new \Smalot\PdfParser\Parser();
                    $pdf = $parser->parseFile($file->getPathname());
                    $text .= "\n" . $pdf->getText();
                } catch (\Exception $e) {
                    return back()->with('error', 'Failed to extract text from PDF: ' . $e->getMessage());
                }
            } else if ($file->getClientOriginalExtension() === 'txt') {
                $text .= "\n" . file_get_contents($file->getPathname());
            }
        }

        // Check if text is long enough
        if (strlen(trim($text)) < 50) {
            return back()->with('error', 'The provided material is too short to generate quality questions.');
        }

        // Call Gemini API
        $apiKey = env('GEMINI_API_KEY');
        if (!$apiKey) {
            return back()->with('error', 'GEMINI_API_KEY is not configured in the .env file.');
        }

        $count = $request->input('question_count');
        $prompt = "You are an expert educational AI. Based on the following material, generate exactly {$count} multiple-choice questions. \n" .
                  "Ensure that there are 4 options per question and only 1 option is correct.\n" .
                  "Output strictly as a JSON array (without markdown wrappers or codeblocks) with each object in this exact format:\n" .
                  "[\n" .
                  "  {\n" .
                  "    \"content\": \"Original question text\",\n" .
                  "    \"points\": 1,\n" .
                  "    \"answers\": [\n" .
                  "      {\"answer_text\": \"Option A\", \"is_correct\": true},\n" .
                  "      {\"answer_text\": \"Option B\", \"is_correct\": false},\n" .
                  "      {\"answer_text\": \"Option C\", \"is_correct\": false},\n" .
                  "      {\"answer_text\": \"Option D\", \"is_correct\": false}\n" .
                  "    ]\n" .
                  "  }\n" .
                  "]\n\n" .
                  "Material:\n" . \Illuminate\Support\Str::limit($text, 20000); // limit to avoid massive token usage

        try {
            $response = Http::timeout(60)->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key={$apiKey}", [
                'contents' => [
                    ['role' => 'user', 'parts' => [['text' => $prompt]]]
                ],
                'generationConfig' => [
                    'response_mime_type' => 'application/json',
                    'temperature' => 0.4
                ]
            ]);

            if ($response->failed()) {
                Log::error('Gemini API Error', ['response' => $response->body()]);
                return back()->with('error', 'Failed to communicate with the AI service.');
            }

            $body = $response->json();
            $generatedText = $body['candidates'][0]['content']['parts'][0]['text'] ?? '';
            
            // Clean up possible markdown code blocks if the AI still returned them despite instructions
            $generatedText = preg_replace('/```json/i', '', $generatedText);
            $generatedText = preg_replace('/```/i', '', $generatedText);
            
            $questionsData = json_decode(trim($generatedText), true);

            if (json_last_error() !== JSON_ERROR_NONE || !is_array($questionsData)) {
                Log::error('AI Parsing Error', ['output' => $generatedText]);
                return back()->with('error', 'AI returned an invalid format. Please try again.');
            }

            // Save to database
            foreach ($questionsData as $qData) {
                // Determine question type assuming MC based on prompt
                $question = Question::create([
                    'quiz_id' => $quiz->id,
                    'type' => 'multiple_choice',
                    'content' => $qData['content'] ?? 'Generated Question',
                    'points' => $qData['points'] ?? 1,
                    'is_reusable' => false,
                ]);

                if (!empty($qData['answers']) && is_array($qData['answers'])) {
                    foreach ($qData['answers'] as $aData) {
                        Answer::create([
                            'question_id' => $question->id,
                            'answer_text' => $aData['answer_text'] ?? 'Option',
                            'is_correct' => $aData['is_correct'] ?? false,
                        ]);
                    }
                }
            }

            return back()->with('success', "Successfully generated {$count} questions from the material!");

        } catch (\Exception $e) {
            Log::error('AI Generation Exception', ['exception' => $e->getMessage()]);
            return back()->with('error', 'An error occurred during generation: ' . $e->getMessage());
        }
    }
}
