<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\OneTimePassword;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Show the code entry screen. A code is sent automatically when none is waiting,
     * so accounts created before this feature can still verify.
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        /** @var User $user */
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->intended($user->dashboardRoute());
        }

        $sendFailed = false;

        if (! OneTimePassword::hasActiveCode($user->email, OneTimePassword::PURPOSE_EMAIL_VERIFICATION)) {
            try {
                $user->sendEmailVerificationNotification();
            } catch (\Throwable $exception) {
                report($exception);
                $sendFailed = true;
            }
        }

        return view('auth.verify-email', ['email' => $user->email, 'sendFailed' => $sendFailed]);
    }
}
