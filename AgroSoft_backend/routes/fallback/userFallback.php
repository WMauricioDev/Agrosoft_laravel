<?php

use Illuminate\Support\Facades\Route;

Route::fallback(function () {
    $path = request()->path();

    if (str_starts_with($path, 'api/user/secondRegister')) {
        return response()->json([
            'success' => false,
            'message' => 'Ruta de usuarios no encontrada (registro secundario)',
        ], 404);
    }

    if (str_starts_with($path, 'api/user/masivRegister')) {
        return response()->json([
            'success' => false,
            'message' => 'Ruta de usuarios no encontrada (registro masivo)',
        ], 404);
    }

    if (str_starts_with($path, 'api/user')) {
        return response()->json([
            'success' => false,
            'message' => 'Ruta de usuarios no encontrada',
        ], 404);
    }

    if (str_starts_with($path, 'api/login')) {
        return response()->json([
            'success' => false,
            'message' => 'Ruta de login no encontrada',
        ], 404);
    }

    if (str_starts_with($path, 'api/register')) {
        return response()->json([
            'success' => false,
            'message' => 'Ruta de registro no encontrada',
        ], 404);
    }

    if (str_starts_with($path, 'api/roles')) {
        return response()->json([
            'success' => false,
            'message' => 'Ruta de roles no encontrada',
        ], 404);
    }

    return response()->json([
        'success' => false,
        'message' => 'Ruta no encontrada',
    ], 404);
});
