<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SingleDeviceLogin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && !$user->isAdmin()) {
            $currentSessionId = $request->session()->getId();
            $storedSessionId  = $user->session_id;

            if ($storedSessionId && $storedSessionId !== $currentSessionId) {
                // ✅ Update DB supaya sync dengan session terbaru
                // (bukan logout — karena Laravel auto-regenerate session ID)
                $user->updateQuietly(['session_id' => $currentSessionId]);
            }
        }

        return $next($request);
    }
}