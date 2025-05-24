<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string $permission)
    {
        $user = Auth::guard('sanctum')->user(); // or Auth::user() for web

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        foreach ($user->roles as $role) {
            if ($role->permissions->contains('name', $permission)) {
                return $next($request);
            }
        }

        return response()->json(['message' => 'Unauthorized'], 403);
    }
}