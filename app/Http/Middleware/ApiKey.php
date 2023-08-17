<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class ApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('APP_KEY');
        $key = '$2y$10$Wh/pDJSowUsfl1.cg/D.xuwoEZyFUes5sDSBzU9ERYga97iJVsQo2';

        if (Hash::check($token, $key)) {
            return $next($request);
        } else {
            return response()->json([
                'message' => 'Api dikunci dengan key!'
            ], 401);
        }
    }
}
