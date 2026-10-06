<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\VerifyEmailCodeRequest;
use App\Models\OneTimePassword;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class PasswordResetCodeController extends Controller
{
    public const EMAIL_SESSION_KEY = 'password_reset_email';

    /**
     * Show the screen where the reset code is entered.
     */
    public function show(): RedirectResponse|View
    {
        $email = Session::get(self::EMAIL_SESSION_KEY);

        if (! $email) {
            return redirect()->route('password.request');
        }

        return view('auth.reset-code', ['email' => $email]);
    }

    /**
     * Verify the reset code and open the new-password step.
     */
    public function verify(VerifyEmailCodeRequest $request): RedirectResponse
    {
        $email = Session::get(self::EMAIL_SESSION_KEY);

        if (! $email) {
            return redirect()->route('password.request');
        }

        if (! OneTimePassword::verifyCode($email, OneTimePassword::PURPOSE_PASSWORD_RESET, $request->validated('code'))) {
            return back()->withErrors(['code' => 'The OTP is incorrect or has expired. Check your email or request a new OTP.']);
        }

        Session::put(NewPasswordController::VERIFIED_EMAIL_SESSION_KEY, $email);

        return redirect()->route('password.reset');
    }

    /**
     * Send a fresh reset code to the email waiting in the session.
     */
    public function resend(): RedirectResponse
    {
        $email = Session::get(self::EMAIL_SESSION_KEY);

        if (! $email) {
            return redirect()->route('password.request');
        }

        if (OneTimePassword::isInCooldown($email, OneTimePassword::PURPOSE_PASSWORD_RESET)) {
            return back()->withErrors(['code' => 'Please wait a minute before requesting another OTP.']);
        }

        $user = User::query()->where('email', $email)->first();

        if ($user) {
            try {
                $user->sendOneTimePassword(OneTimePassword::PURPOSE_PASSWORD_RESET);
            } catch (\Throwable $exception) {
                report($exception);

                return back()->withErrors(['code' => 'We could not send the OTP right now. Please try again shortly.']);
            }
        }

        return back()->with('status', 'A new OTP has been sent.');
    }
}
