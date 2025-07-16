<?php

namespace App\Http\Controllers\Usuarios;
use App\Models\Usuarios\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Usuarios\CambiarPasswordRequest;

class CambiarPasswordController extends Controller
{
public function cambiarPassword(CambiarPasswordRequest $request)
{
    $usuario = auth()->user(); // El usuario autenticado mediante token

    if (!$usuario) {
        return response()->json([
            'success' => false,
            'message' => 'No autenticado.',
        ], 401);
    }

    if (!Hash::check($request->actual_password, $usuario->password)) {
        return response()->json([
            'success' => false,
            'message' => 'La contraseña actual no es correcta.',
        ], 400);
    }

    $usuario->password = Hash::make($request->nueva_password);
    $usuario->save();

    return response()->json([
        'success' => true,
        'message' => 'Contraseña actualizada correctamente.',
    ]);
}
}
