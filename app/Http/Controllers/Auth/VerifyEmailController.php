<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\VerifyEmailCodeRequest;
use App\Models\OneTimePassword;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Check the submitted code and mark the authenticated user's email as verified.
     */
    public function __invoke(VerifyEmailCodeRequest $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        if (! OneTimePassword::verifyCode($user->email, OneTimePassword::PURPOSE_EMAIL_VERIFICATION, $request->validated('code'))) {
            return back()->withErrors(['code' => 'The OTP is incorrect or has expired. Check your email or request a new OTP.']);
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return redirect()->intended($user->dashboardRoute())
            ->with('status', 'Your email has been verified.');
    }
}
