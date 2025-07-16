<?php

namespace App\Http\Controllers\Trazabilidad;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\Trazabilidad\Cultivo;
use App\Http\Requests\Trazabilidad\StoreCultivoRequest;
use App\Http\Requests\Trazabilidad\UpdateCultivoRequest;

class CultivoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $cultivos = Cultivo::with(['especie', 'bancal', 'unidadMedida'])->get();
        return response()->json([
            'success' => true,
            'message' => 'Cultivo obtenido correctamente.',
            'data' => $cultivos,
        ], 201);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCultivoRequest $request): JsonResponse
    {
        $data = $request->validated();
        $cultivo = Cultivo::create($data);
        $cultivo->load(['especie', 'bancal', 'unidadMedida']);
        return response()->json([
            'success' => true,
            'message' => 'Cultivo creado correctamente.',
            'data' => $cultivo,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Cultivo $cultivo): JsonResponse
    {
        $cultivo->load(['especie', 'bancal', 'unidadMedida']);
        return response()->json($cultivo);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCultivoRequest $request, Cultivo $cultivo): JsonResponse
    {
        $data = $request->validated();
        $cultivo->update($data);
        $cultivo->load(['especie', 'bancal', 'unidadMedida']);
        return response()->json([
            'success' => true,
            'message' => 'Cultivo actualizado correctamente.',
            'data' => $cultivo,
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cultivo $cultivo): JsonResponse
    {
        $cultivo->delete();
        return response()->json([
            'success' => true,
            'message' => 'Cultivo eliminado correctamente.',
        ], 201);
    }
}