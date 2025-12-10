<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureFrontendRequestsAreStateful
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Configure CORS headers for frontend requests
        $response = $next($request);

        // Add CORS headers if not already present
        if (!$response->headers->has('Access-Control-Allow-Origin')) {
            $frontendUrl = config('app.frontend_url', 'http://localhost:3000');

            $response->headers->set('Access-Control-Allow-Origin', $frontendUrl);
            $response->headers->set('Access-Control-Allow-Credentials', 'true');
            $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Accept, Authorization, X-XSRF-TOKEN, X-Requested-With');
        }

        return $response;
    }
}
