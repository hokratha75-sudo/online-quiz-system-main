<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreQuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        $quizId = $this->input('quiz_id');
        $quiz = \App\Models\Quiz::find($quizId);
        
        $user = $this->user();
        return $user && ($user->isAdmin() || (int)$quiz?->created_by === (int)$user->id);
    }

    public function rules(): array
    {
        $isShortAnswer = $this->input('type') === 'short_answer';

        return [
            'quiz_id'    => 'required|exists:quizzes,id',
            'content'    => 'required|string|max:5000',
            'type'       => 'required|string|in:single_choice,multiple_choice,true_false,short_answer',
            'points'     => 'required|integer|min:1|max:100',
            'options'    => $isShortAnswer ? 'nullable|array' : 'required|array|min:2',
            'options.*'  => $isShortAnswer ? 'nullable|string|max:1000' : 'required|string|max:1000',
            'correct'    => $isShortAnswer ? 'nullable|array' : 'required|array|min:1',
            'correct.*'  => 'integer|min:0',
            'is_reusable' => 'sometimes|boolean',
        ];
    }

    protected function prepareForValidation()
    {
        $safeTags = '<b><i><u><br><p>';
        
        $content = strip_tags($this->input('content'), $safeTags);
        
        $options = $this->input('options', []);
        if (is_array($options)) {
            $options = array_map(fn($opt) => strip_tags($opt), $options);
            $options = array_filter($options, fn($opt) => !empty(trim($opt)));
            $options = array_values($options);
        }
        
        $correct = $this->input('correct', []);
        if (is_array($correct)) {
            $correct = array_values(array_filter($correct, fn($c) => is_numeric($c)));
        }
        
        $this->merge([
            'content' => $content,
            'options' => $options,
            'correct' => $correct,
        ]);
    }

    public function messages(): array
    {
        return [
            'quiz_id.required' => 'Quiz ID is required',
            'quiz_id.exists' => 'Quiz does not exist',
            'content.required' => 'Question content is required',
            'points.required' => 'Points are required',
            'points.min' => 'Points must be at least 1',
            'type.required' => 'Question type is required',
            'type.in' => 'Invalid question type',
            'options.required' => 'At least 2 options are required',
            'options.min' => 'At least 2 options are required',
            'correct.required' => 'At least 1 correct answer is required',
            'correct.min' => 'At least 1 correct answer is required',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $validator->errors()
        ], 422));
    }
}