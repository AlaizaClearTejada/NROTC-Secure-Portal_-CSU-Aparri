<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SendPasswordResetCodeRequest;
use App\Models\OneTimePassword;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the forgot password form.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Send an OTP to a registered email address.
     */
    public function store(SendPasswordResetCodeRequest $request): RedirectResponse
    {
        $email = strtolower($request->validated('email'));
        $user = User::query()->where('email', $email)->first();

        if (! $user) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'This email is not registered in the system.']);
        }

        if (OneTimePassword::isInCooldown($email, OneTimePassword::PURPOSE_PASSWORD_RESET)) {
            Session::put(PasswordResetCodeController::EMAIL_SESSION_KEY, $email);

            return redirect()->route('password.otp')
                ->with('status', 'An OTP was already sent. Please wait a minute before requesting another.');
        }

        try {
            $user->sendOneTimePassword(OneTimePassword::PURPOSE_PASSWORD_RESET);
        } catch (\Throwable $exception) {
            report($exception);

            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'We could not send the OTP right now. Please try again shortly.']);
        }

        Session::put(PasswordResetCodeController::EMAIL_SESSION_KEY, $email);
        Session::forget(NewPasswordController::VERIFIED_EMAIL_SESSION_KEY);

        return redirect()->route('password.otp')
            ->with('status', 'An OTP has been sent to your email.');
    }
}
