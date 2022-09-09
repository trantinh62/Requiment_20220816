<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ForceJsonResponse
{
    public function handle(Request $request, Closure $next)
    {
        $request->headers->set('Accept', 'application/json');
        $request->headers->set('Access-Control-Allow-Origin', $request->headers->get('origin'));
        $request->headers->set('Access-Control-Allow-Methods', '*');
        return $next($request);
    }
}
