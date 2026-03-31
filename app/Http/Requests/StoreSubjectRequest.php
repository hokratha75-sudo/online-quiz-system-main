<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        $subjectId = $this->route('subject') ? $this->route('subject')->id : null;

        return [
            'subject_name' => [
                'required_without:name',
                'string',
                'max:255',
                \Illuminate\Validation\Rule::unique('subjects', 'subject_name')->ignore($subjectId),
            ],
            'name' => [
                'required_without:subject_name',
                'string',
                'max:255',
                \Illuminate\Validation\Rule::unique('subjects', 'subject_name')->ignore($subjectId),
            ],
            'department_id' => 'nullable|exists:departments,id',
            'major_id' => 'nullable|exists:majors,id',
            'code' => [
                'nullable',
                'string',
                'max:50',
                \Illuminate\Validation\Rule::unique('subjects', 'code')->ignore($subjectId),
            ],
            'credits' => 'nullable|integer|min:1|max:10',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'subject_name' => strip_tags($this->subject_name ?? $this->name),
            'code' => strip_tags($this->code),
        ]);
    }
}
