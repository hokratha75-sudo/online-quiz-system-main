<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MajorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $majorId = $this->route('major') ? $this->route('major')->id : null;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('majors', 'name')->ignore($majorId),
            ],
            'code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('majors', 'code')->ignore($majorId),
            ],
            'description' => [
                'nullable',
                'string',
            ],
        ];
    }
}
