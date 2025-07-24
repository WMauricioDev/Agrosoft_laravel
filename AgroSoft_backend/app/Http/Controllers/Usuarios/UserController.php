<?php

namespace App\Http\Controllers\Usuarios;

use App\Models\Usuarios\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\Usuarios\SecondRegisterRequest;  
use App\Http\Requests\Usuarios\UpdateUserRequest;
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
public function store(SecondRegisterRequest $request)
{

    if (!$request->isMethod('post')) {
        return response()->json([
            'success' => false,
            'message' => 'Método no permitido. Solo se acepta POST',
        ], 405);
    }

    $data = $request->validated();

    if (empty($data['password'])) {
        $primeraLetra = strtolower(substr($data['nombre'], 0, 1));
        $generatedPassword = $primeraLetra . $data['numero_documento'];
        $data['password'] = $generatedPassword;
    } else {
        $generatedPassword = $data['password'];
    }

    $data['password'] = Hash::make($data['password']);
    $data['email'] = $data['email'] ?? 'sin-email-' . uniqid() . '@example.com'; 
    $data['rol_id'] = $data['rol_id'] ?? 1;  
    $data['estado'] = $data['estado'] ?? true; 

    try {
        $user = User::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Usuario creado exitosamente',
            'data'    => $user,
            'password'=> $generatedPassword,  
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
public function show($id): JsonResponse
{
    // Intentamos encontrar al usuario por ID
    $user = User::with('rol')->find($id);

    // Si el usuario no se encuentra, devolvemos una respuesta 404 personalizada
    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'Usuario no encontrado.',
        ], Response::HTTP_NOT_FOUND);
    }

    // Si lo encontramos, devolvemos la información del usuario
    return response()->json([
        'success' => true,
        'data' => [
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
        ]
    ], Response::HTTP_OK);
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
public function update(UpdateUserRequest $request, $id)
{
    $user = User::find($id);    

     if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'Usuario no encontrado.',
        ], Response::HTTP_NOT_FOUND);
    }
    
    // Evitar que el usuario con ID 1 sea editado
    if ($user->id === 1) {
        return response()->json([
            'success' => false,
            'message' => 'El usuario principal no puede ser editado',
        ], \Symfony\Component\HttpFoundation\Response::HTTP_FORBIDDEN);
    }

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'Usuario no encontrado.',
        ], \Symfony\Component\HttpFoundation\Response::HTTP_NOT_FOUND);
    }
    

    $user->update($request->only(['nombre', 'apellido', 'estado', 'rol_id', 'email']));
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
    ], Response::HTTP_OK);

}

    /**
     * Remove the specified resource from storage.
     */

public function destroy($id)
{
       $user = User::find($id);

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'Usuario no encontrado.',
        ], Response::HTTP_NOT_FOUND);
    }
    if ($user->rol_id == 4) {
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
