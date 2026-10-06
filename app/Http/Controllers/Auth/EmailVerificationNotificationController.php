<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\OneTimePassword;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new verification code.
     */
    public function store(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->intended($user->dashboardRoute());
        }

        if (OneTimePassword::isInCooldown($user->email, OneTimePassword::PURPOSE_EMAIL_VERIFICATION)) {
            return back()->withErrors(['code' => 'Please wait a minute before requesting another OTP.']);
        }

        try {
            $user->sendEmailVerificationNotification();
        } catch (\Throwable $exception) {
            report($exception);

            return back()->withErrors(['code' => 'We could not send the OTP right now. Please try again shortly.']);
        }

        return back()->with('status', 'verification-code-sent');
    }
}
