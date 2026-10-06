<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\CompleteRegistrationRequest;
use App\Http\Requests\Auth\StoreRegistrationRequest;
use App\Http\Requests\Auth\VerifyEmailCodeRequest;
use App\Models\OneTimePassword;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public const PENDING_SESSION_KEY = 'registration.details';

    public const VERIFIED_SESSION_KEY = 'registration.verified_email';

    /**
     * Step 1: show the cadet details form.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Step 1: keep the details in the session and email an OTP.
     * No account is created until the email is verified and a password is set.
     */
    public function store(StoreRegistrationRequest $request): RedirectResponse
    {
        $details = $request->validated();
        $details['email'] = strtolower($details['email']);

        Session::put(self::PENDING_SESSION_KEY, $details);
        Session::forget(self::VERIFIED_SESSION_KEY);

        try {
            $this->sendCode($details);
        } catch (\Throwable $exception) {
            report($exception);

            return redirect()->route('register.verify')
                ->withErrors(['code' => 'We could not send the OTP. Use "Resend OTP" in a moment.']);
        }

        return redirect()->route('register.verify');
    }

    /**
     * Step 2: show the code entry screen.
     */
    public function showVerify(): RedirectResponse|View
    {
        $details = Session::get(self::PENDING_SESSION_KEY);

        if (! $details) {
            return redirect()->route('register');
        }

        return view('auth.register-verify', ['email' => $details['email']]);
    }

    /**
     * Step 2: check the code. A correct code unlocks the password step.
     */
    public function verify(VerifyEmailCodeRequest $request): RedirectResponse
    {
        $details = Session::get(self::PENDING_SESSION_KEY);

        if (! $details) {
            return redirect()->route('register');
        }

        if (! OneTimePassword::verifyCode($details['email'], OneTimePassword::PURPOSE_EMAIL_VERIFICATION, $request->validated('code'))) {
            return back()->withErrors(['code' => 'The OTP is incorrect or has expired. Check your email or request a new OTP.']);
        }

        Session::put(self::VERIFIED_SESSION_KEY, $details['email']);

        return redirect()->route('register.password');
    }

    /**
     * Step 2: send a fresh code, subject to the resend cooldown.
     */
    public function resend(): RedirectResponse
    {
        $details = Session::get(self::PENDING_SESSION_KEY);

        if (! $details) {
            return redirect()->route('register');
        }

        if (OneTimePassword::isInCooldown($details['email'], OneTimePassword::PURPOSE_EMAIL_VERIFICATION)) {
            return back()->withErrors(['code' => 'Please wait a minute before requesting another OTP.']);
        }

        try {
            $this->sendCode($details);
        } catch (\Throwable $exception) {
            report($exception);

            return back()->withErrors(['code' => 'We could not send the OTP right now. Please try again shortly.']);
        }

        return back()->with('status', 'A new OTP has been sent.');
    }

    /**
     * Step 3: show the password form. Only reachable after the code was verified.
     */
    public function showPassword(): RedirectResponse|View
    {
        $email = Session::get(self::VERIFIED_SESSION_KEY);

        if (! $email) {
            return redirect()->route('register');
        }

        return view('auth.register-password', ['email' => $email]);
    }

    /**
     * Step 3: create the account with the verified email and the chosen password.
     */
    public function storePassword(CompleteRegistrationRequest $request): RedirectResponse
    {
        $details = Session::get(self::PENDING_SESSION_KEY);
        $verifiedEmail = Session::get(self::VERIFIED_SESSION_KEY);

        if (! $details || $verifiedEmail !== $details['email']) {
            return redirect()->route('register');
        }

        if (User::query()->where('email', $details['email'])->orWhere('student_id', $details['student_id'])->exists()) {
            Session::forget([self::PENDING_SESSION_KEY, self::VERIFIED_SESSION_KEY]);

            return redirect()->route('register')
                ->withErrors(['email' => 'An account with this email or student ID was just created. Sign in instead.']);
        }

        $user = User::create([
            'name' => $this->fullName($details),
            'student_id' => $details['student_id'],
            'email' => $details['email'],
            'password' => Hash::make($request->validated('password')),
            'role' => User::ROLE_CADET,
            'is_active' => false,
        ]);

        $user->markEmailAsVerified();

        OneTimePassword::forget($details['email'], OneTimePassword::PURPOSE_EMAIL_VERIFICATION);
        Session::forget([self::PENDING_SESSION_KEY, self::VERIFIED_SESSION_KEY]);

        return redirect()->route('login', ['from' => 'enroll'])
            ->with('status', 'Account created. Sign in with your Student ID to continue with your enrollment application.');
    }

    /**
     * Email an OTP for the pending registration.
     *
     * @param  array<string, mixed>  $details
     */
    private function sendCode(array $details): void
    {
        OneTimePassword::send($details['email'], OneTimePassword::PURPOSE_EMAIL_VERIFICATION, $details['first_name']);
    }

    /**
     * Build the stored name in "Last, First Middle Suffix" form.
     *
     * @param  array<string, mixed>  $details
     */
    private function fullName(array $details): string
    {
        return trim(implode(' ', array_filter([
            $details['last_name'].',',
            $details['first_name'],
            $details['middle_name'] ?? null,
            $details['suffix'] ?? null,
        ])));
    }
}
