<?php

namespace App\Http\Controllers\Officer;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CadetController extends Controller
{
    public function index(Request $request): View
    {
        $cadets = User::query()
            ->where('role', User::ROLE_CADET)
            ->where('enrollment_status', 'approved')
            ->orderBy('name')
            ->get();

        return view('officer.cadets.index', compact('cadets'));
    }

    public function show(User $user): View
    {
        abort_if($user->role !== User::ROLE_CADET || $user->enrollment_status !== 'approved', 404);

        return view('officer.cadets.show', compact('user'));
    }
}
