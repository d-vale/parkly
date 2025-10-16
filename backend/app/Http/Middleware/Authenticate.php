<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    protected function redirectTo(Request $request): ?string
    {
        // Si c'est une requête JSON/API, ne pas rediriger (retourner null = erreur 401)
        if ($request->expectsJson()) {
            return null;
        }

        // Sinon, rediriger vers le frontend
        return env('FRONTEND_URL', 'http://localhost:3000');
    }
}
