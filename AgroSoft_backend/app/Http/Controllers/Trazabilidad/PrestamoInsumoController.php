<?php

namespace App\Http\Controllers\Trazabilidad;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Trazabilidad\PrestamoInsumo;

class PrestamoInsumoController extends Controller
{
    public function index(): JsonResponse
    {
        $prestamos = PrestamoInsumo::with(['actividad', 'insumo', 'unidadMedida'])->get();
        Log::info('Fetched all PrestamoInsumo records', ['count' => $prestamos->count()]);
        return response()->json($prestamos);
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'actividad_id' => 'required|exists:actividades,id',
                'insumo_id' => 'required|exists:insumos,id',
                'cantidad_usada' => 'required|integer|min:0',
                'cantidad_devuelta' => 'required|integer|min:0',
                'fecha_devolucion' => 'nullable|date',
                'unidad_medida_id' => 'nullable|exists:unidad_medidas,id',
            ]);

            Log::info('Validated PrestamoInsumo data', ['data' => $validated]);

            $prestamo = DB::transaction(function () use ($validated) {
                return PrestamoInsumo::create($validated);
            });

            $prestamo->load(['actividad', 'insumo', 'unidadMedida']);
            Log::info('Created PrestamoInsumo', ['id' => $prestamo->id]);
            return response()->json($prestamo, 201);
        } catch (\Exception $e) {
            Log::error('Failed to create PrestamoInsumo', ['error' => $e->getMessage(), 'data' => $request->all()]);
            return response()->json(['error' => 'Failed to create PrestamoInsumo: ' . $e->getMessage()], 500);
        }
    }

    public function show(PrestamoInsumo $prestamoInsumo): JsonResponse
    {
        Log::info('Fetched PrestamoInsumo', ['id' => $prestamoInsumo->id]);
        $prestamoInsumo->load(['actividad', 'insumo', 'unidadMedida']);
        return response()->json($prestamoInsumo);
    }

    public function update(Request $request, PrestamoInsumo $prestamoInsumo): JsonResponse
    {
        try {
            $validated = $request->validate([
                'actividad_id' => 'required|exists:actividades,id',
                'insumo_id' => 'required|exists:insumos,id',
                'cantidad_usada' => 'required|integer|min:0',
                'cantidad_devuelta' => 'required|integer|min:0',
                'fecha_devolucion' => 'nullable|date',
                'unidad_medida_id' => 'nullable|exists:unidad_medidas,id',
            ]);

            Log::info('Validated PrestamoInsumo update data', ['id' => $prestamoInsumo->id, 'data' => $validated]);

            $prestamoInsumo->update($validated);
            $prestamoInsumo->load(['actividad', 'insumo', 'unidadMedida']);
            Log::info('Updated PrestamoInsumo', ['id' => $prestamoInsumo->id]);
            return response()->json($prestamoInsumo);
        } catch (\Exception $e) {
            Log::error('Failed to update PrestamoInsumo', ['id' => $prestamoInsumo->id, 'error' => $e->getMessage(), 'data' => $request->all()]);
            return response()->json(['error' => 'Failed to update PrestamoInsumo: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(PrestamoInsumo $prestamoInsumo): JsonResponse
    {
        try {
            Log::info('Deleting PrestamoInsumo', ['id' => $prestamoInsumo->id]);
            $prestamoInsumo->delete();
            Log::info('Deleted PrestamoInsumo', ['id' => $prestamoInsumo->id]);
            return response()->json(null, 204);
        } catch (\Exception $e) {
            Log::error('Failed to delete PrestamoInsumo', ['id' => $prestamoInsumo->id, 'error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to delete PrestamoInsumo: ' . $e->getMessage()], 500);
        }
    }
}