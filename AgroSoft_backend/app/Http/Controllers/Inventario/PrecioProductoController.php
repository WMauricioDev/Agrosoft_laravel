<?php

namespace App\Http\Controllers\Inventario;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Inventario\PrecioProducto;

class PrecioProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $preciosProductos = PrecioProducto::with(['cosecha', 'unidadMedida'])->get();
        Log::info('Fetched all PrecioProducto records', ['count' => $preciosProductos->count()]);
        return response()->json($preciosProductos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'cosecha_id' => 'nullable|exists:cosechas,id',
                'unidad_medida_id' => 'nullable|exists:unidad_medidas,id',
                'precio' => 'required|numeric|min:0',
                'fecha_registro' => 'required|date',
                'stock' => 'required|integer|min:0',
                'fecha_caducidad' => 'nullable|date|after_or_equal:fecha_registro',
            ]);

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
            return response()->json(['error' => 'Failed to create PrecioProducto: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PrecioProducto $precioProducto): JsonResponse
    {
        Log::info('Fetched PrecioProducto', ['id' => $precioProducto->id]);
        $precioProducto->load(['cosecha', 'unidadMedida']);
        return response()->json($precioProducto);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PrecioProducto $precioProducto): JsonResponse
    {
        try {
            $validated = $request->validate([
                'cosecha_id' => 'nullable|exists:cosechas,id',
                'unidad_medida_id' => 'nullable|exists:unidad_medidas,id',
                'precio' => 'required|numeric|min:0',
                'fecha_registro' => 'required|date',
                'stock' => 'required|integer|min:0',
                'fecha_caducidad' => 'nullable|date|after_or_equal:fecha_registro',
            ]);

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
            return response()->json(['error' => 'Failed to update PrecioProducto: ' . $e->getMessage()], 500);
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
            return response()->json(['error' => 'Failed to delete PrecioProducto: ' . $e->getMessage()], 500);
        }
    }
}