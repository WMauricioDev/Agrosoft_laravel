<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class IsUserAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if (! $user) {
                return response()->json([
                    'message' => 'No autorizado: token inválido',
                ], Response::HTTP_UNAUTHORIZED);
            }

            if (! $user->estado) {
                return response()->json([
                    'message' => 'Usuario inactivo. Acceso denegado.',
                ], Response::HTTP_FORBIDDEN);
            }

            return $next($request);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error de autenticación: ' . $e->getMessage(),
            ], Response::HTTP_UNAUTHORIZED);
        }
    }
}
