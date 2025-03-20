<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        
        $apiKey = $request->header('X-API-KEY');
        $validApiKey = env('API_KEY'); 
        if ($apiKey && $apiKey === $validApiKey) {
            return $next($request);
        }
        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
