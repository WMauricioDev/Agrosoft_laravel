<?php

namespace App\Http\Controllers\Inventario;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Inventario\BodegaInsumo;
use App\Http\Requests\Inventario\StoreBodegaInsumoRequest;
use App\Http\Requests\Inventario\UpdateBodegaInsumoRequest;

class BodegaInsumoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $bodegaInsumos = BodegaInsumo::with(['bodega', 'insumo'])->get();
            Log::info('Fetched all BodegaInsumo records', ['count' => $bodegaInsumos->count()]);
            return response()->json($bodegaInsumos);
        } catch (\Exception $e) {
            Log::error('Failed to fetch BodegaInsumo records', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Error al obtener los registros de BodegaInsumo: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBodegaInsumoRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            Log::info('Validated BodegaInsumo data', ['data' => $validated]);

            $bodegaInsumo = DB::transaction(function () use ($validated) {
                return BodegaInsumo::create($validated);
            });

            $bodegaInsumo->load(['bodega', 'insumo']);
            Log::info('Created BodegaInsumo', ['id' => $bodegaInsumo->id]);

            return response()->json($bodegaInsumo, 201);
        } catch (\Exception $e) {
            Log::error('Failed to create BodegaInsumo', [
                'error' => $e->getMessage(),
                'data' => $request->all(),
            ]);
            return response()->json(['error' => 'Error al crear BodegaInsumo: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(BodegaInsumo $bodegaInsumo): JsonResponse
    {
        try {
            Log::info('Fetched BodegaInsumo', ['id' => $bodegaInsumo->id]);
            $bodegaInsumo->load(['bodega', 'insumo']);
            return response()->json($bodegaInsumo);
        } catch (\Exception $e) {
            Log::error('Failed to fetch BodegaInsumo', [
                'id' => $bodegaInsumo->id,
                'error' => $e->getMessage(),
            ]);
            return response()->json(['error' => 'Error al obtener BodegaInsumo: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBodegaInsumoRequest $request, BodegaInsumo $bodegaInsumo): JsonResponse
    {
        try {
            $validated = $request->validated();
            Log::info('Validated BodegaInsumo update data', ['id' => $bodegaInsumo->id, 'data' => $validated]);

            $bodegaInsumo->update($validated);
            $bodegaInsumo->load(['bodega', 'insumo']);
            Log::info('Updated BodegaInsumo', ['id' => $bodegaInsumo->id]);

            return response()->json($bodegaInsumo);
        } catch (\Exception $e) {
            Log::error('Failed to update BodegaInsumo', [
                'id' => $bodegaInsumo->id,
                'error' => $e->getMessage(),
                'data' => $request->all(),
            ]);
            return response()->json(['error' => 'Error al actualizar BodegaInsumo: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BodegaInsumo $bodegaInsumo): JsonResponse
    {
        try {
            Log::info('Deleting BodegaInsumo', ['id' => $bodegaInsumo->id]);
            $bodegaInsumo->delete();
            Log::info('Deleted BodegaInsumo', ['id' => $bodegaInsumo->id]);
            return response()->json(null, 204);
        } catch (\Exception $e) {
            Log::error('Failed to delete BodegaInsumo', [
                'id' => $bodegaInsumo->id,
                'error' => $e->getMessage(),
            ]);
            return response()->json(['error' => 'Error al eliminar BodegaInsumo: ' . $e->getMessage()], 500);
        }
    }
}