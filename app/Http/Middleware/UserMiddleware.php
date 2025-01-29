<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
       Log::info('usermiddleware');
        if (!auth()->guard('sanctum')->check()) {
            return response()->json(['message' => 'You cannot access this page! Please login!'], 401);
        }
        if (auth()->guard('sanctum')->user()->role !== 'user') {
            return response()->json(['message' => 'You cannot access this page!'], 403);
        }
        return $next($request);
    }
}
