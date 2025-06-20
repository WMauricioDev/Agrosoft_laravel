<?php

namespace App\Http\Controllers\Usuarios;

use App\Models\Usuarios\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
