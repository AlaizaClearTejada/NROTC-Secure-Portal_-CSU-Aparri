<?php

namespace App\Http\Controllers\Cadet;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    /**
     * Store or replace the authenticated cadet's 2x2 profile photo.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'photo' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        /** @var User $user */
        $user = Auth::user();

        // Delete the old photo if one exists.
        if ($user->photo_path) {
            Storage::disk('public')->delete($user->photo_path);
        }

        $path = $request->file('photo')->store('photos', 'public');

        $user->update(['photo_path' => $path]);

        return back()->with('success', 'Profile photo updated successfully.');
    }
}
