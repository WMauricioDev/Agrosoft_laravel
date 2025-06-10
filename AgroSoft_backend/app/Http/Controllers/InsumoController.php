<?php

namespace App\Http\Controllers;

use App\Models\Insumo;
use App\Http\Requests\StoreInsumoRequest;
use App\Http\Requests\UpdateInsumoRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class InsumoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $insumos = Insumo::all();

            $message = $insumos->isEmpty()
                ? 'No hay insumos registrados'
                : 'Insumos obtenidos con éxito';

            return response()->json([
                'message' => $message,
                'data'    => $insumos,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error obteniendo insumos: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error interno al obtener insumos.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInsumoRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $insumo = Insumo::create($data);

            return response()->json([
                'message' => 'Insumo creado exitosamente',
                'data'    => $insumo,
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creando insumo: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error interno al crear insumo.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Insumo $insumo): JsonResponse
    {
        try {
            return response()->json([
                'message' => 'Insumo obtenido con éxito',
                'data'    => $insumo,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error obteniendo insumo: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error interno al obtener insumo.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInsumoRequest $request, Insumo $insumo): JsonResponse
    {
        try {
            $data = $request->validated();
            $insumo->update($data);

            return response()->json([
                'message' => 'Insumo actualizado exitosamente',
                'data'    => $insumo,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error actualizando insumo: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error interno al actualizar insumo.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Insumo $insumo): JsonResponse
    {
        try {
            $insumo->delete();

            return response()->json([
                'message' => 'Insumo eliminado correctamente',
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error eliminando insumo: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error interno al eliminar insumo.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
