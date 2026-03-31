<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
        return [
            'quiz_id' => 'required|exists:quizzes,id',
            'content' => 'required|string|max:5000',
            'type' => 'required|string|in:single_choice,multiple_choice,boolean,true_false,short_answer',
            'points' => 'required|integer|min:1|max:100',
            'options' => 'required_unless:type,short_answer|array|min:2|max:10',
            'options.*' => 'required_unless:type,short_answer|string|max:1000',
            'correct' => 'required_unless:type,short_answer|array|min:1',
            'correct.*' => 'integer|min:0|max:10',
        ];
    }

    protected function prepareForValidation()
    {
        // XSS Protection: Strip potentially dangerous tags
        $safeTags = '<b><i><u><br><p>';
        $this->merge([
            'content' => strip_tags($this->content, $safeTags),
            'options' => array_map(fn($opt) => strip_tags($opt), $this->input('options', [])),
        ]);
    }
}
