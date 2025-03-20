<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class AutoLogoutMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
{
    $timeout = config('session.lifetime') * 60; 
    Log::info('Middleware Triggered. User Authenticated: ' . (Auth::check() ? 'Yes' : 'No'));
    
    if (Auth::check()) {
        $lastActivity = Session::get('lastActivityTime');
        
        Log::info('Last Activity Time: ' . ($lastActivity ? $lastActivity : 'Not Set'));

        if ($lastActivity && (time() - $lastActivity) > $timeout) {
            Log::info('Logging out user due to inactivity.');
            Auth::logout();
            Session::flush();
            return response()->json(['message' => 'Logged out due to inactivity'], 401);
        }

        Session::put('lastActivityTime', time());
        Log::info('Updated Last Activity Time: ' . time());
    }

    return $next($request);
}

}
