<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EnrollmentController extends Controller
{
    public function index(Request $request): View
    {
        $filter = $request->input('status', User::ENROLLMENT_PENDING_REVIEW);
        $courseYearFilter = $request->input('course_year');

        $query = User::query()
            ->where('role', User::ROLE_CADET)
            ->whereNotNull('enrollment_status');

        if ($courseYearFilter) {
            $query->where('course_year', $courseYearFilter);
        }

        $enrollees = (clone $query)
            ->when($filter !== 'all', fn ($q) => $q->where('enrollment_status', $filter))
            ->orderByRaw("FIELD(enrollment_status, 'pending_review', 'under_medical_review', 'revision_requested', 'approved', 'rejected')")
            ->orderBy('name')
            ->get();

        $counts = (clone $query)
            ->selectRaw("
                COUNT(*) as total,
                SUM(enrollment_status = 'pending_review')       as pending,
                SUM(enrollment_status = 'under_medical_review') as medical,
                SUM(enrollment_status = 'revision_requested')   as revision,
                SUM(enrollment_status = 'approved')             as approved,
                SUM(enrollment_status = 'rejected')             as rejected
            ")
            ->first();

        $courseYears = User::where('role', User::ROLE_CADET)
            ->whereNotNull('course_year')
            ->distinct()
            ->pluck('course_year')
            ->sort()
            ->values();

        return view('admin.enrollments.index', compact('enrollees', 'counts', 'filter', 'courseYearFilter', 'courseYears'));
    }

    public function approveAll(Request $request): RedirectResponse
    {
        $courseYearFilter = $request->input('course_year');

        $query = User::query()
            ->where('role', User::ROLE_CADET)
            ->where('enrollment_status', User::ENROLLMENT_PENDING_REVIEW);
            
        if ($courseYearFilter) {
            $query->where('course_year', $courseYearFilter);
        }

        $count = $query->update([
            'enrollment_status' => User::ENROLLMENT_APPROVED,
            'is_active'         => true,
        ]);

        return redirect()->back()->with('success', "{$count} enrollment(s) have been approved and activated.");
    }

    public function show(User $user): View
    {
        abort_if($user->role !== User::ROLE_CADET, 404);

        return view('admin.enrollments.show', compact('user'));
    }

    public function flagMedical(Request $request, User $user): RedirectResponse
    {
        abort_if($user->role !== User::ROLE_CADET, 404);

        $user->update([
            'enrollment_status' => User::ENROLLMENT_MEDICAL_REVIEW,
        ]);

        return redirect()->route('admin.enrollments.show', $user)
            ->with('success', "{$user->name}'s application has been flagged for medical review.");
    }

    public function requestRevision(Request $request, User $user): RedirectResponse
    {
        abort_if($user->role !== User::ROLE_CADET, 404);

        $request->validate(['revision_notes' => 'required|string|max:1000']);

        $user->update([
            'enrollment_status' => User::ENROLLMENT_REVISION_REQUESTED,
            'revision_notes'    => $request->input('revision_notes'),
        ]);

        return redirect()->route('admin.enrollments.show', $user)
            ->with('success', "Revision requested for {$user->name}'s application.");
    }

    public function validateEnrollment(Request $request, User $user): RedirectResponse
    {
        abort_if($user->role !== User::ROLE_CADET, 404);

        $user->update([
            'enrollment_status'  => User::ENROLLMENT_APPROVED,
            'enrollment_remarks' => $request->input('remarks'),
            'is_active'          => true,
        ]);

        return redirect()->route('admin.enrollments.index')
            ->with('success', "{$user->name}'s enrollment has been approved and activated.");
    }

    public function reject(Request $request, User $user): RedirectResponse
    {
        abort_if($user->role !== User::ROLE_CADET, 404);

        $request->validate(['remarks' => 'nullable|string|max:500']);

        $user->update([
            'enrollment_status'  => User::ENROLLMENT_REJECTED,
            'enrollment_remarks' => $request->input('remarks'),
            'is_active'          => false,
        ]);

        return redirect()->route('admin.enrollments.index')
            ->with('success', "{$user->name}'s enrollment has been rejected.");
    }
}
