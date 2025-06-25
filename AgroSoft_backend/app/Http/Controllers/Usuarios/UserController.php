<?php

namespace App\Http\Controllers\Usuarios;

use App\Models\Usuarios\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $usuarios = User::with('rol')->get();

    return response()->json($usuarios);
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
  $data = $request->validate([
        'nombre'           => 'required|string|max:100',
        'apellido'         => 'required|string|max:100',
        'numero_documento' => 'required|numeric|unique:users,numero_documento',
       
    ]);
    if (empty($data['password'])) {
        $primeraLetra = strtolower(substr($data['nombre'], 0, 1));
        $generatedPassword = $primeraLetra . $data['numero_documento'];
        $data['password'] = $generatedPassword;
    } else {
        $generatedPassword = $data['password'];
    }

    $data['password'] = Hash::make($data['password']);
    $data['email'] = $data['email'] ?? 'sin-email-' . uniqid() . '@example.com'; // email de relleno
    // Valores por defecto
    $data['rol_id'] = $data['rol_id'] ?? 1;
    $data['estado'] = $data['estado'] ?? true;

    try {
        $user = User::create($data);

        return response()->json([
            'success'  => true,
            'message'  => 'Usuario creado exitosamente',
            'data'     => $user,
            'password' => $generatedPassword,
        ], Response::HTTP_CREATED);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage(),
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}


    /**
     * Display the specified resource.
     */
   public function show(User $user): JsonResponse
{
    $user->load('rol');

    return response()->json([
        'id' => $user->id,
        'nombre' => $user->nombre,
        'apellido' => $user->apellido,
        'email' => $user->email,
        'numero_documento' => $user->numero_documento,
        'estado' => $user->estado,
        'rol' => [
            'id' => $user->rol->id,
            'nombre' => $user->rol->nombre,
        ],
    ]);
}


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
public function update(Request $request, User $user)
{
    // Evitar que el usuario con ID 1 sea editado
    if ($user->id === 1) {
        return response()->json([
            'success' => false,
            'message' => 'El usuario principal no puede ser editado',
        ], \Symfony\Component\HttpFoundation\Response::HTTP_FORBIDDEN);
    }

    $user->update($request->only(['nombre', 'apellido', 'estado', 'rol_id', 'email']));

    return response()->json([
        'success' => true,
        'message' => 'Usuario actualizado',
        'user'    => $user
    ]);
}


    /**
     * Remove the specified resource from storage.
     */

public function destroy(User $user)
{
    if ($user->rol_id == 1) {
        return response()->json([
            'success' => false,
            'message' => 'No se puede eliminar un usuario con rol de administrador',
        ], Response::HTTP_FORBIDDEN);
    }

    try {
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Usuario eliminado correctamente',
        ], Response::HTTP_OK);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al eliminar el usuario: ' . $e->getMessage(),
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}

}
