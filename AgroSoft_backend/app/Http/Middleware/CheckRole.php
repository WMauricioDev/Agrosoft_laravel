<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param mixed ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user(); // ← Usa Auth::user() en lugar de auth()->user()

        if (!$user || !in_array($user->role, $roles)) {
            return response()->json(['message' => 'No autorizado.'], 403);
        }

        return $next($request);
    }
}
