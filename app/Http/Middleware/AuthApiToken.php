<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;

class AuthApiToken
{
    public function handle($request, Closure $next)
    {
        $token = $request->bearerToken();

        if (! $token || ! User::where('api_token', $token)->first()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
