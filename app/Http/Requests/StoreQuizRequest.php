<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuizRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check(); // AdminMiddleware handles the rest, but we can be specific
    }

    public function rules(): array
    {
        return [
            'title'          => 'required|string|max:255',
            'subject_id'     => 'required|exists:subjects,id',
            'status'         => 'required|in:draft,published',
            'description'    => 'nullable|string|max:1000',
            'time_limit'     => 'nullable|integer|min:1|max:180',
            'opened_at'      => 'nullable|date',
            'closed_at'      => 'nullable|date|after_or_equal:opened_at',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'title' => strip_tags($this->title),
            'description' => strip_tags($this->description, '<b><i><u><br><p>'),
        ]);
    }
}
