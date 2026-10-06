<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EnrollmentController extends Controller
{
    public function index(Request $request): View
    {
        $filter = $request->input('status', User::ENROLLMENT_PENDING_REVIEW);

        $enrollees = User::query()
            ->where('role', User::ROLE_CADET)
            ->whereNotNull('enrollment_status')
            ->when($filter !== 'all', fn ($q) => $q->where('enrollment_status', $filter))
            ->orderByRaw("FIELD(enrollment_status, 'pending_review', 'under_medical_review', 'revision_requested', 'approved', 'rejected')")
            ->orderBy('name')
            ->get();

        $counts = User::where('role', User::ROLE_CADET)
            ->whereNotNull('enrollment_status')
            ->selectRaw("
                COUNT(*) as total,
                SUM(enrollment_status = 'pending_review')       as pending,
                SUM(enrollment_status = 'under_medical_review') as medical,
                SUM(enrollment_status = 'revision_requested')   as revision,
                SUM(enrollment_status = 'approved')             as approved,
                SUM(enrollment_status = 'rejected')             as rejected
            ")
            ->first();

        return view('officer.enrollments.index', compact('enrollees', 'counts', 'filter'));
    }

    public function show(User $user): View
    {
        abort_if($user->role !== User::ROLE_CADET, 404);

        return view('officer.enrollments.show', compact('user'));
    }
}
