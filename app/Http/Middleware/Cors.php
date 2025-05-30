<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Cors
{
    /**
     * Handle an incoming request by manipulating the response headers to define
     * Cross-Origin Resource Sharing (CORS) policies.
     *
     * @param Request $request The incoming HTTP request.
     * @param Closure $next The next middleware to be executed.
     * @return mixed           The HTTP response with modified headers.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $response->headers->set('Access-Control-Allow-Origin', config('app.frontend_url', 'http://localhost:3000'));
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');
        $response->headers->set('Access-Control-Allow-Credentials', 'true');

        return $response;
    }
}
