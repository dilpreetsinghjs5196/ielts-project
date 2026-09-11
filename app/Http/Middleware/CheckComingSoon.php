<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckComingSoon
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $comingSoon = \App\Models\Setting::where('key', 'coming_soon')->first()->value ?? '0';
        
        if ($comingSoon == '1') {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['error' => 'Coming Soon', 'message' => 'This feature is currently under development.'], 403);
            }
            return response()->view('student.coming_soon');
        }

        return $next($request);
    }
}
