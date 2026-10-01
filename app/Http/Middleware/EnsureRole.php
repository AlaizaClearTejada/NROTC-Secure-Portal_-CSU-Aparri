<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * EnsureRole – Role-Based Access Control (RBAC) middleware.
 *
 * Enforces the principle of least privilege (OWASP A01 – Broken Access Control):
 *   • Unauthenticated users are redirected to the login page.
 *   • Deactivated accounts are logged out immediately.
 *   • Users whose role does not match any of the allowed roles receive a 403.
 *
 * Usage in routes:
 *   Route::middleware('role:admin')          // single role
 *   Route::middleware('role:admin,officer')  // multiple allowed roles
 */
class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Force-logout deactivated accounts even if a valid session exists.
        // Exception: pending-enrollment cadets are allowed to stay logged in
        // so they can access and submit their enrollment form.
        if (!$user->is_active) {
            if ($user->isCadet() && in_array($user->enrollment_status, [
                null, 
                \App\Models\User::ENROLLMENT_PENDING_REVIEW,
                \App\Models\User::ENROLLMENT_MEDICAL_REVIEW,
                \App\Models\User::ENROLLMENT_REVISION_REQUESTED
            ], true)) {
                return $user->enrollment_status === null 
                    ? redirect()->route('enroll.form') 
                    : redirect()->route('cadet.applicant.dashboard');
            }

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->withErrors(['email' => 'Your account has been deactivated. Contact the administrator.']);
        }

        if (!in_array($user->role, $roles, strict: true)) {
            abort(403, 'Access denied. You do not have the required permissions to view this page.');
        }

        return $next($request);
    }
}
