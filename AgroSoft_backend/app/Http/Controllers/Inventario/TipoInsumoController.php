<?php

namespace App\Http\Controllers\Inventario;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Inventario\TipoInsumo;
use App\Http\Requests\Inventario\StoreTipoInsumoRequest;
use App\Http\Requests\Inventario\UpdateTipoInsumoRequest;

class TipoInsumoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $tiposInsumo = TipoInsumo::all();
            Log::info('Fetched all TipoInsumo records', ['count' => $tiposInsumo->count()]);
            return response()->json($tiposInsumo);
        } catch (\Exception $e) {
            Log::error('Failed to fetch TipoInsumo records', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Error al obtener los tipos de insumo: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTipoInsumoRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            Log::info('Validated TipoInsumo data', ['data' => $validated]);

            $tipoInsumo = DB::transaction(function () use ($validated) {
                return TipoInsumo::create($validated);
            });

            Log::info('Created TipoInsumo', ['id' => $tipoInsumo->id, 'nombre' => $tipoInsumo->nombre]);
            return response()->json($tipoInsumo, 201);
        } catch (\Exception $e) {
            Log::error('Failed to create TipoInsumo', [
                'error' => $e->getMessage(),
                'data' => $request->all(),
            ]);
            return response()->json(['error' => 'Error al crear el tipo de insumo: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(TipoInsumo $tipoInsumo): JsonResponse
    {
        try {
            Log::info('Fetched TipoInsumo', ['id' => $tipoInsumo->id]);
            return response()->json($tipoInsumo);
        } catch (\Exception $e) {
            Log::error('Failed to fetch TipoInsumo', [
                'id' => $tipoInsumo->id,
                'error' => $e->getMessage(),
            ]);
            return response()->json(['error' => 'Error al obtener el tipo de insumo: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTipoInsumoRequest $request, TipoInsumo $tipoInsumo): JsonResponse
    {
        try {
            $validated = $request->validated();
            Log::info('Validated TipoInsumo update data', ['id' => $tipoInsumo->id, 'data' => $validated]);

            $tipoInsumo->update($validated);
            Log::info('Updated TipoInsumo', ['id' => $tipoInsumo->id]);

            return response()->json($tipoInsumo);
        } catch (\Exception $e) {
            Log::error('Failed to update TipoInsumo', [
                'id' => $tipoInsumo->id,
                'error' => $e->getMessage(),
                'data' => $request->all(),
            ]);
            return response()->json(['error' => 'Error al actualizar el tipo de insumo: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TipoInsumo $tipoInsumo): JsonResponse
    {
        try {
            Log::info('Deleting TipoInsumo', ['id' => $tipoInsumo->id]);
            $tipoInsumo->delete();
            Log::info('Deleted TipoInsumo', ['id' => $tipoInsumo->id]);
            return response()->json(null, 204);
        } catch (\Exception $e) {
            Log::error('Failed to delete TipoInsumo', [
                'id' => $tipoInsumo->id,
                'error' => $e->getMessage(),
            ]);
            return response()->json(['error' => 'Error al eliminar el tipo de insumo: ' . $e->getMessage()], 500);
        }
    }
}