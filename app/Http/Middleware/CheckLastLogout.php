<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;

class CheckLastLogout
{
    public function handle($request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $payload = JWTAuth::parseToken()->getPayload();

            $tokenLastLogout = (int) $payload->get('last_logout', 0);
            $currentLastLogout = $user->last_logout ? (int) $user->last_logout->timestamp : 0;

            if ($tokenLastLogout !== $currentLastLogout) {
                return response()->json(['error' => 'Session expired – previously logged out from all devices'], 401);
            }

            $request->merge(['user' => $user]);

            return $next($request);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }
}
