<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show the admin dashboard with unit statistics and recent accounts.
     */
    public function index(): View
    {
        $stats = [
            'total_users' => User::count(),
            'total_officers' => User::where('role', User::ROLE_OFFICER)->count(),
            'total_cadets' => User::where('role', User::ROLE_CADET)->count(),
            'active_users' => User::where('is_active', true)->count(),
            'locked_accounts' => User::whereNotNull('locked_until')
                ->where('locked_until', '>', now())
                ->count(),
        ];

        $recent_users = User::latest()->take(10)->get();

        return view('admin.dashboard', compact('stats', 'recent_users'));
    }

    /**
     * Show the form for creating a new user account.
     */
    public function createUser(): View
    {
        return view('admin.create-user');
    }

    /**
     * Store a new user account.
     * Passwords are hashed automatically by the `hashed` cast on the User model.
     */
    public function storeUser(StoreUserRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $fullName = trim(implode(' ', array_filter([
            $validated['first_name'],
            $validated['middle_name'] ?? null,
            $validated['last_name'],
        ])));

        $user = User::create([
            'name' => $fullName,
            'student_id' => $validated['student_id'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => $validated['role'],
            'is_active' => true,
        ]);

        try {
            $user->sendEmailVerificationNotification();
        } catch (\Throwable $exception) {
            report($exception);

            return redirect()->route('admin.dashboard')
                ->with('success', "Account for {$fullName} created successfully.")
                ->with('error', 'The OTP could not be emailed. The user can request one after signing in.');
        }

        return redirect()->route('admin.dashboard')
            ->with('success', "Account for {$fullName} created successfully. An OTP was sent to {$user->email}.");
    }

    /**
     * Unlock a locked account and reset its failed-attempt counter.
     */
    public function unlockAccount(User $user): RedirectResponse
    {
        $user->update([
            'login_attempts' => 0,
            'locked_until' => null,
        ]);

        return back()->with('success', "{$user->name}'s account has been unlocked.");
    }

    /**
     * Toggle the active / inactive state of an account.
     */
    public function toggleActive(User $user): RedirectResponse
    {
        $user->update(['is_active' => ! $user->is_active]);

        $state = $user->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "{$user->name}'s account has been {$state}.");
    }
}
