<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SingleDeviceLogin
{
    /**
     * Handle an incoming request.
     *
     * Ensure non-admin users can only be logged in on one device at a time.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && !$user->is_admin) {
            $currentSessionId = $request->session()->getId();
            $storedSessionId = $user->session_id;

            // If session IDs don't match, logout this user
            if ($storedSessionId && $storedSessionId !== $currentSessionId) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')->with('error', 'Your account has been logged in from another device.');
            }
        }

        return $next($request);
    }
}

