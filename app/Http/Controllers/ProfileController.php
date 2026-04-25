<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function show()
    {
        return view('profile.show', ['user' => Auth::user()]);
    }

    public function editPhoto()
    {
        return view('profile.photo', ['user' => Auth::user()]);
    }

    public function updatePhoto(Request $request)
    {
        $request->validate(['photo' => ['required', 'string']]);

        $user      = Auth::user();
        $base64    = preg_replace('/^data:image\/\w+;base64,/', '', $request->photo);
        $decoded   = base64_decode($base64);
        $filename  = 'profile_' . $user->id . '_' . now()->format('Ymd_His') . '.jpg';
        $path      = 'profile_photos/' . $filename;

        if ($user->photo_path) {
            Storage::disk('public')->delete($user->photo_path);
        }

        Storage::disk('public')->put($path, $decoded);
        $user->update(['photo_path' => $path]);

        return back()->with('success', 'Profile photo updated successfully!');
    }

    public function editPassword()
    {
        return view('profile.password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ]);

        $user = Auth::user();
        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Password changed successfully!');
    }

    public function editDetails()
    {
        $user = Auth::user();
        if ($user->isUser()) {
            return redirect()->route('profile.show')->with('error', 'Only admin can edit personal details.');
        }
        return view('profile.details', ['user' => Auth::user()]);
    }

    public function updateDetails(Request $request)
    {
        $user = Auth::user();
        if ($user->isUser()) {
            return back()->with('error', 'Only admin can edit personal details.');
        }

        $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'department' => ['nullable', 'string', 'max:255'],
        ]);

        $user->update($request->only('name', 'department'));

        return back()->with('success', 'Details updated successfully!');
    }

    public function activity()
    {
        $attendances = Auth::user()
            ->attendances()
            ->orderBy('date', 'desc')
            ->take(30)
            ->get();

        return view('profile.activity', compact('attendances'));
    }
}
