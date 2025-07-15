<?php

namespace App\Http\Controllers\Inventario;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Inventario\Bodega;
use App\Http\Requests\Inventario\StoreBodegaRequest;
use App\Http\Requests\Inventario\UpdateBodegaRequest;

class BodegaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $bodegas = Bodega::all();
            Log::info('Fetched all Bodega records', ['count' => $bodegas->count()]);
            return response()->json($bodegas);
        } catch (\Exception $e) {
            Log::error('Failed to fetch Bodega records', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Error al obtener las bodegas: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBodegaRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            Log::info('Validated Bodega data', ['data' => $validated]);

            $bodega = DB::transaction(function () use ($validated) {
                return Bodega::create($validated);
            });

            Log::info('Created Bodega', ['id' => $bodega->id, 'nombre' => $bodega->nombre]);
            return response()->json($bodega, 201);
        } catch (\Exception $e) {
            Log::error('Failed to create Bodega', [
                'error' => $e->getMessage(),
                'data' => $request->all(),
            ]);
            return response()->json(['error' => 'Error al crear la bodega: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Bodega $bodega): JsonResponse
    {
        try {
            Log::info('Fetched Bodega', ['id' => $bodega->id]);
            return response()->json($bodega);
        } catch (\Exception $e) {
            Log::error('Failed to fetch Bodega', [
                'id' => $bodega->id,
                'error' => $e->getMessage(),
            ]);
            return response()->json(['error' => 'Error al obtener la bodega: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBodegaRequest $request, Bodega $bodega): JsonResponse
    {
        try {
            $validated = $request->validated();
            Log::info('Validated Bodega update data', ['id' => $bodega->id, 'data' => $validated]);

            $bodega->update($validated);
            Log::info('Updated Bodega', ['id' => $bodega->id]);

            return response()->json($bodega);
        } catch (\Exception $e) {
            Log::error('Failed to update Bodega', [
                'id' => $bodega->id,
                'error' => $e->getMessage(),
                'data' => $request->all(),
            ]);
            return response()->json(['error' => 'Error al actualizar la bodega: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bodega $bodega): JsonResponse
    {
        try {
            Log::info('Deleting Bodega', ['id' => $bodega->id]);
            $bodega->delete();
            Log::info('Deleted Bodega', ['id' => $bodega->id]);
            return response()->json(null, 204);
        } catch (\Exception $e) {
            Log::error('Failed to delete Bodega', [
                'id' => $bodega->id,
                'error' => $e->getMessage(),
            ]);
            return response()->json(['error' => 'Error al eliminar la bodega: ' . $e->getMessage()], 500);
        }
    }
}