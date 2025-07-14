<?php

namespace App\Http\Controllers\Inventario;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Inventario\Insumo;
use App\Http\Requests\Inventario\UpdateInsumoRequest;

class InsumoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $insumos = Insumo::with(['unidadMedida', 'tipoInsumo'])->get();
        Log::info('Fetched all Insumo records', ['count' => $insumos->count()]);
        return response()->json($insumos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'nombre' => 'required|string|max:255',
                'descripcion' => 'required|string',
                'cantidad' => 'required|integer|min:1',
                'unidad_medida_id' => 'nullable|exists:unidad_medidas,id',
                'tipo_insumo_id' => 'nullable|exists:tipo_insumos,id',
                'activo' => 'boolean',
                'tipo_empacado' => 'nullable|string|max:100',
                'fecha_caducidad' => 'nullable|date',
                'precio_insumo' => 'required|numeric|min:0',
            ]);

            Log::info('Validated Insumo data', ['data' => $validated]);

            $insumo = DB::transaction(function () use ($validated) {
                return Insumo::create($validated);
            });

            $insumo->load(['unidadMedida', 'tipoInsumo']);
            Log::info('Created Insumo', ['id' => $insumo->id, 'nombre' => $insumo->nombre]);

            return response()->json($insumo, 201);
        } catch (\Exception $e) {
            Log::error('Failed to create Insumo', [
                'error' => $e->getMessage(),
                'data' => $request->all(),
            ]);
            return response()->json(['error' => 'Failed to create Insumo: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Insumo $insumo): JsonResponse
    {
        Log::info('Fetched Insumo', ['id' => $insumo->id]);
        $insumo->load(['unidadMedida', 'tipoInsumo']);
        return response()->json($insumo);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInsumoRequest $request, Insumo $insumo): JsonResponse
    {
        try {
            $validated = $request->validated();
    
            Log::info('Validated Insumo update data', ['id' => $insumo->id, 'data' => $validated]);
    
            $insumo->update($validated);
            $insumo->load(['unidadMedida', 'tipoInsumo']);
    
            Log::info('Updated Insumo', ['id' => $insumo->id]);
    
            return response()->json($insumo);
        } catch (\Exception $e) {
            Log::error('Failed to update Insumo', [
                'id' => $insumo->id,
                'error' => $e->getMessage(),
                'data' => $request->all(),
            ]);
    
            return response()->json(['error' => 'Failed to update Insumo: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Insumo $insumo): JsonResponse
    {
        try {
            Log::info('Deleting Insumo', ['id' => $insumo->id]);
            $insumo->delete();
            Log::info('Deleted Insumo', ['id' => $insumo->id]);
            return response()->json(null, 204);
        } catch (\Exception $e) {
            Log::error('Failed to delete Insumo', [
                'id' => $insumo->id,
                'error' => $e->getMessage(),
            ]);
            return response()->json(['error' => 'Failed to delete Insumo: ' . $e->getMessage()], 500);
        }
    }
}