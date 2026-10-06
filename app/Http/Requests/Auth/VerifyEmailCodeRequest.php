<?php

namespace App\Http\Requests\Auth;

use App\Models\OneTimePassword;
use Illuminate\Foundation\Http\FormRequest;

class VerifyEmailCodeRequest extends FormRequest
{
    /**
     * Authorization is handled by the route middleware.
     */
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, array<int, string>> */
    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'digits:'.OneTimePassword::CODE_LENGTH],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'code.required' => 'Enter the OTP sent to your email.',
            'code.digits' => 'The OTP must be '.OneTimePassword::CODE_LENGTH.' digits.',
        ];
    }
}
