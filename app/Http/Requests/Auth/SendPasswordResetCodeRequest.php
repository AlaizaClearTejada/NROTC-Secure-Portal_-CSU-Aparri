<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class SendPasswordResetCodeRequest extends FormRequest
{
    /**
     * Anyone may request an OTP; the controller keeps the response generic.
     */
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, array<int, string>> */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email', 'max:255'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'email.required' => 'Enter the email address on your account.',
            'email.email' => 'Enter a valid email address.',
        ];
    }
}
