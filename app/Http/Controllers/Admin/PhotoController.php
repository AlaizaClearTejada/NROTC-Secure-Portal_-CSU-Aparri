<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    /**
     * Store or replace a user's 2x2 profile photo on behalf of the admin.
     */
    public function store(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'photo' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        // Delete the old photo if one exists.
        if ($user->photo_path) {
            Storage::disk('public')->delete($user->photo_path);
        }

        $path = $request->file('photo')->store('photos', 'public');

        $user->update(['photo_path' => $path]);

        return back()->with('success', "Photo updated for {$user->name}.");
    }

    /**
     * Remove a user's profile photo.
     */
    public function destroy(User $user): RedirectResponse
    {
        if ($user->photo_path) {
            Storage::disk('public')->delete($user->photo_path);
            $user->update(['photo_path' => null]);
        }

        return back()->with('success', "Photo removed for {$user->name}.");
    }
}
