<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RolMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = auth('api')->user();

        if (!$user || !$user->rol || !in_array(strtolower($user->rol->nombre), $roles)) {
            return response()->json([
                'success' => false,
                'message' => 'Acceso denegado. No tienes el rol requerido.'
            ], 403);
        }

        return $next($request);
    }
}
