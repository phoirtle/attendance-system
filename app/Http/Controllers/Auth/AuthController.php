<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route(
                Auth::user()->isAdmin() ? 'admin.dashboard' : 'attendance.index'
            );
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $role = $request->input('role', 'user');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            // Enforce role match
            if ($user->role !== $role) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Role mismatch. Please select the correct role.',
                ])->withInput($request->only('email', 'role'));
            }

            $request->session()->regenerate();

            // Single device login for non-admin users
            if ($user->isUser()) {
                $user->update(['session_id' => $request->session()->getId()]);
            }

            return redirect()->intended(
                $user->isAdmin() ? route('admin.dashboard') : route('attendance.index')
            );
        }

        return back()->withErrors([
            'email' => 'These credentials do not match our records.',
        ])->withInput($request->only('email', 'role'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
