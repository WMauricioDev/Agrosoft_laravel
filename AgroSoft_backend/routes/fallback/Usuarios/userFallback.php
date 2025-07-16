<?php

use Illuminate\Support\Facades\Route;

Route::fallback(function () {
    $path = request()->path();

    if (str_starts_with($path, 'api/user/secondRegister')) {
        return response()->json([
            'success' => false,
            'message' => 'Ruta de usuarios no encontrada (registro secundario), ruta correcta: api/secondRegister',
        ], 404);
    }

    if (str_starts_with($path, 'api/user/masivRegister')) {
        return response()->json([
            'success' => false,
            'message' => 'Ruta de usuarios no encontrada (registro masivo), ruta correcta: api/masivRegister',
        ], 404);
    }

    if (str_starts_with($path, 'api/user')) {
        return response()->json([
            'success' => false,
            'message' => 'Ruta de usuarios no encontrada, ruta correcta: api/user',
        ], 404);
    }

    if (str_starts_with($path, 'api/login')) {
        return response()->json([
            'success' => false,
            'message' => 'Ruta de login no encontrada, ruta correcta: api/login',
        ], 404);
    }

    if (str_starts_with($path, 'api/register')) {
        return response()->json([
            'success' => false,
            'message' => 'Ruta de registro no encontrada, ruta correcta: api/register',
        ], 404);
    }

    if (str_starts_with($path, 'api/roles')) {
        return response()->json([
            'success' => false,
            'message' => 'Ruta de roles no encontrada, ruta correcta: api/roles',
        ], 404);
    }

    return response()->json([
        'success' => false,
        'message' => 'Ruta no encontrada',
    ], 404);
});
