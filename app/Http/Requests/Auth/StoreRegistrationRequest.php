<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRegistrationRequest extends FormRequest
{
    /**
     * Anyone may start a registration; the account is only created after email verification.
     */
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        return [
            'last_name' => ['required', 'string', 'max:100'],
            'first_name' => ['required', 'string', 'max:100'],
            'middle_name' => ['nullable', 'string', 'max:100'],
            'suffix' => ['nullable', 'string', 'max:10'],
            'student_id' => ['required', 'string', 'max:50', Rule::unique(User::class, 'student_id')],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class, 'email')],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'last_name.required' => 'Enter your last name.',
            'first_name.required' => 'Enter your first name.',
            'student_id.required' => 'Enter your student ID.',
            'student_id.unique' => 'This student ID is already registered.',
            'email.required' => 'Enter your email address.',
            'email.email' => 'Enter a valid email address.',
            'email.unique' => 'This email address is already registered.',
        ];
    }
}
