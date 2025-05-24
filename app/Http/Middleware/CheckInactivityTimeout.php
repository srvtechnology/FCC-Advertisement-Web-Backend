<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckInactivityTimeout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
{
    $user = $request->user();

    if ($user && $user->last_activity_at) {
        $inactivityLimit = now()->subMinutes(5);

        if ($user->last_activity_at < $inactivityLimit) {
            // Optionally revoke token too
            $user->tokens()->delete();

            return response()->json(['message' => 'Session expired due to inactivity.'], 401);
        }

        // Update last activity timestamp
        $user->update(['last_activity_at' => now()]);
    }

    return $next($request);
}

}
