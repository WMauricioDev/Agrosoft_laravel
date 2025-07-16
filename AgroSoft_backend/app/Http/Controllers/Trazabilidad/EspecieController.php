<?php

namespace App\Http\Controllers\Trazabilidad;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\Trazabilidad\Especie;
use App\Http\Requests\Trazabilidad\StoreEspecieRequest;
use App\Http\Requests\Trazabilidad\UpdateEspecieRequest;

class EspecieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $especies = Especie::with('tipoEspecie')->get();
        return response()->json([
            'success' => true,
            'message' => 'Especies obtenidas correctamente.',
            'data' => $especies,
        ], 201);   
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEspecieRequest $request): JsonResponse
    {
        $data = $request->validated();
        $especie = Especie::create($data);
        $especie->load('tipoEspecie');
        return response()->json([
            'success' => true,
            'message' => 'Especie creada correctamente.',
            'data' => $especie,
        ], 201);   
    }

    /**
     * Display the specified resource.
     */
    public function show(Especie $especie): JsonResponse
    {
        $especie->load('tipoEspecie');
        return response()->json($especie);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEspecieRequest $request, Especie $especie): JsonResponse
    {
        $data = $request->validated();
        $especie->update($data);
        $especie->load('tipoEspecie');
        return response()->json([
            'success' => true,
            'message' => 'Especie actualizado correctamente.',
            'data' => $especie,
        ], 201);   
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Especie $especie): JsonResponse
    {
        $especie->delete();
        return response()->json([
            'success' => true,
            'message' => 'Especie eliminada correctamente.',
        ], 201);   
    }
}