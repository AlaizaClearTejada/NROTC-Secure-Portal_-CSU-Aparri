<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Models\OneTimePassword;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    public const VERIFIED_EMAIL_SESSION_KEY = 'password_reset_verified_email';

    /**
     * Show the new-password form. Only reachable after the reset code was verified.
     */
    public function create(): RedirectResponse|View
    {
        $email = Session::get(self::VERIFIED_EMAIL_SESSION_KEY);

        if (! $email) {
            return redirect()->route('password.request');
        }

        return view('auth.reset-password', ['email' => $email]);
    }

    /**
     * Save the new password and clear the reset session.
     */
    public function store(ResetPasswordRequest $request): RedirectResponse
    {
        $email = Session::get(self::VERIFIED_EMAIL_SESSION_KEY);

        if (! $email) {
            return redirect()->route('password.request');
        }

        $user = User::query()->where('email', $email)->first();

        if (! $user) {
            return redirect()->route('password.request');
        }

        $user->forceFill([
            'password' => Hash::make($request->validated('password')),
            'remember_token' => Str::random(60),
            'login_attempts' => 0,
            'locked_until' => null,
        ])->save();

        OneTimePassword::forget($email, OneTimePassword::PURPOSE_PASSWORD_RESET);
        Session::forget([PasswordResetCodeController::EMAIL_SESSION_KEY, self::VERIFIED_EMAIL_SESSION_KEY]);

        return redirect()->route('login')->with('status', 'Your password has been reset. You can now sign in.');
    }
}
