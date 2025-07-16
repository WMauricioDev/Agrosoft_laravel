<?php

namespace App\Http\Controllers\Inventario;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Inventario\PrecioProducto;
use App\Http\Requests\Inventario\StorePrecioProductoRequest;
use App\Http\Requests\Inventario\UpdatePrecioProductoRequest;

class PrecioProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $preciosProductos = PrecioProducto::with(['cosecha', 'unidadMedida'])->get();
            Log::info('Fetched all PrecioProducto records', ['count' => $preciosProductos->count()]);
            return response()->json($preciosProductos);
        } catch (\Exception $e) {
            Log::error('Failed to fetch PrecioProducto records', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Error al obtener los registros de PrecioProducto: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePrecioProductoRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            Log::info('Validated PrecioProducto data', ['data' => $validated]);

            $precioProducto = DB::transaction(function () use ($validated) {
                return PrecioProducto::create($validated);
            });

            $precioProducto->load(['cosecha', 'unidadMedida']);
            Log::info('Created PrecioProducto', ['id' => $precioProducto->id]);

            return response()->json($precioProducto, 201);
        } catch (\Exception $e) {
            Log::error('Failed to create PrecioProducto', [
                'error' => $e->getMessage(),
                'data' => $request->all(),
            ]);
            return response()->json(['error' => 'Error al crear PrecioProducto: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PrecioProducto $precioProducto): JsonResponse
    {
        try {
            Log::info('Fetched PrecioProducto', ['id' => $precioProducto->id]);
            $precioProducto->load(['cosecha', 'unidadMedida']);
            return response()->json($precioProducto);
        } catch (\Exception $e) {
            Log::error('Failed to fetch PrecioProducto', [
                'id' => $precioProducto->id,
                'error' => $e->getMessage(),
            ]);
            return response()->json(['error' => 'Error al obtener PrecioProducto: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePrecioProductoRequest $request, PrecioProducto $precioProducto): JsonResponse
    {
        try {
            $validated = $request->validated();
            Log::info('Validated PrecioProducto update data', ['id' => $precioProducto->id, 'data' => $validated]);

            $precioProducto->update($validated);
            $precioProducto->load(['cosecha', 'unidadMedida']);
            Log::info('Updated PrecioProducto', ['id' => $precioProducto->id]);

            return response()->json($precioProducto);
        } catch (\Exception $e) {
            Log::error('Failed to update PrecioProducto', [
                'id' => $precioProducto->id,
                'error' => $e->getMessage(),
                'data' => $request->all(),
            ]);
            return response()->json(['error' => 'Error al actualizar PrecioProducto: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PrecioProducto $precioProducto): JsonResponse
    {
        try {
            Log::info('Deleting PrecioProducto', ['id' => $precioProducto->id]);
            $precioProducto->delete();
            Log::info('Deleted PrecioProducto', ['id' => $precioProducto->id]);
            return response()->json(null, 204);
        } catch (\Exception $e) {
            Log::error('Failed to delete PrecioProducto', [
                'id' => $precioProducto->id,
                'error' => $e->getMessage(),
            ]);
            return response()->json(['error' => 'Error al eliminar PrecioProducto: ' . $e->getMessage()], 500);
        }
    }
}