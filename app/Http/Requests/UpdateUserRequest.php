<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (!auth()->check()) return false;
        
        $userRouteParam = $this->route('user');
        
        // Scenario 1: Admin updating a specific user record
        if ($userRouteParam) {
            return auth()->user()->isAdmin();
        }
        
        // Scenario 2: User updating their own profile via common route
        return true;
    }

    public function rules(): array
    {
        // Use the user from Route if present (Admin view), otherwise use the authenticated user (Self Profile)
        $user = $this->route('user') ?? auth()->user();
        
        $rules = [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            // Optional on update (leave blank to keep current). Match UI guidance: minimum 8 characters.
            'password' => ['nullable', 'string', Password::min(8)->mixedCase()->symbols()],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'birthday' => 'nullable|date',
            'sex' => 'nullable|in:Male,Female',
            // Allow larger uploads (KB). Server-side still enforces an upper bound.
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        // Specific fields only editable by Admin when modifying users
        if (auth()->user()->isAdmin() && $this->route('user')) {
            $rules['username'] = ['required', 'string', 'max:255', 'unique:users,username,' . $user->id];
            $rules['role_id'] = 'required|exists:roles,id';
            $rules['auth_method'] = 'nullable|string|in:manual,ldap';
            $rules['is_suspended'] = 'nullable|boolean';
            $rules['force_password_change'] = 'nullable|boolean';
        }

        return $rules;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'username' => strip_tags($this->username),
            'first_name' => strip_tags($this->first_name),
            'last_name' => strip_tags($this->last_name),
            'email' => $this->email ? filter_var($this->email, FILTER_SANITIZE_EMAIL) : null,
            'is_suspended' => $this->has('is_suspended'),
            'force_password_change' => $this->has('force_password_change'),
        ]);
    }
}
