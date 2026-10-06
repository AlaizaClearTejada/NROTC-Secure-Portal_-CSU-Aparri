<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class CompleteRegistrationRequest extends FormRequest
{
    /**
     * Authorization is handled by the verified-email session check in the controller.
     */
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, array<int, mixed>> */
    public function rules(): array
    {
        return [
            'password' => ['required', 'confirmed', Password::defaults()],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'password.required' => 'Enter a password.',
            'password.confirmed' => 'The passwords do not match.',
        ];
    }
}
