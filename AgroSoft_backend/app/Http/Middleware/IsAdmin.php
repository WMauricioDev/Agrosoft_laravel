<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth('api')->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'No autenticado.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        if ((int) $user->rol_id === 4) {
            return $next($request);
        }

        return response()->json([
            'success' => false,
            'message' => 'No eres administrador.',
        ], Response::HTTP_FORBIDDEN);
    }
}
