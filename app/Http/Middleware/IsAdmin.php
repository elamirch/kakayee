<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return $next($request);
    }
}
