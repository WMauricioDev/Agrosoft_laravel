<?php

use Illuminate\Support\Facades\Route;

Route::fallback(function () {
    $path = request()->path();

    if (str_starts_with($path, 'api/control')) {
        return response()->json([
            'success' => false,
            'message' => 'Ruta de controles no encontrada, ruta correcta: api/control',
        ], 404);
    }

    if (str_starts_with($path, 'api/tipo_control')) {
        return response()->json([
            'success' => false,
            'message' => 'Ruta de tipos de control no encontrada, ruta correcta: api/tipo_control',
        ], 404);
    }


    return response()->json([
        'success' => false,
        'message' => 'Ruta no encontrada',
    ], 404);
});
