<?php

namespace App\Http\Controllers\Trazabilidad;

use App\Models\Trazabilidad\PrestamoHerramienta;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class PrestamoHerramientaController extends Controller
{
    public function index(): JsonResponse
    {
        $prestamos = PrestamoHerramienta::with(['actividad', 'herramienta', 'bodegaHerramienta'])->get();
        Log::info('Fetched all PrestamoHerramienta records', ['count' => $prestamos->count()]);
        return response()->json($prestamos);
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'actividad_id' => 'required|exists:actividades,id',
                'herramienta_id' => 'required|exists:herramientas,id',
                'bodega_herramienta_id' => 'nullable|exists:bodega_herramientas,id',
                'cantidad_entregada' => 'required|integer|min:1',
                'cantidad_devuelta' => 'required|integer|min:0',
                'entregada' => 'required|boolean',
                'devuelta' => 'required|boolean',
                'fecha_devolucion' => 'nullable|date',
            ]);

            Log::info('Validated PrestamoHerramienta data', ['data' => $validated]);

            $prestamo = DB::transaction(function () use ($validated) {
                return PrestamoHerramienta::create($validated);
            });

            $prestamo->load(['actividad', 'herramienta', 'bodegaHerramienta']);
            Log::info('Created PrestamoHerramienta', ['id' => $prestamo->id]);
            return response()->json($prestamo, 201);
        } catch (\Exception $e) {
            Log::error('Failed to create PrestamoHerramienta', ['error' => $e->getMessage(), 'data' => $request->all()]);
            return response()->json(['error' => 'Failed to create PrestamoHerramienta: ' . $e->getMessage()], 500);
        }
    }

    public function show(PrestamoHerramienta $prestamoHerramienta): JsonResponse
    {
        Log::info('Fetched PrestamoHerramienta', ['id' => $prestamoHerramienta->id]);
        $prestamoHerramienta->load(['actividad', 'herramienta', 'bodegaHerramienta']);
        return response()->json($prestamoHerramienta);
    }

    public function update(Request $request, PrestamoHerramienta $prestamoHerramienta): JsonResponse
    {
        try {
            $validated = $request->validate([
                'actividad_id' => 'required|exists:actividades,id',
                'herramienta_id' => 'required|exists:herramientas,id',
                'bodega_herramienta_id' => 'nullable|exists:bodega_herramientas,id',
                'cantidad_entregada' => 'required|integer|min:1',
                'cantidad_devuelta' => 'required|integer|min:0',
                'entregada' => 'required|boolean',
                'devuelta' => 'required|boolean',
                'fecha_devolucion' => 'nullable|date',
            ]);

            Log::info('Validated PrestamoHerramienta update data', ['id' => $prestamoHerramienta->id, 'data' => $validated]);

            $prestamoHerramienta->update($validated);
            $prestamoHerramienta->load(['actividad', 'herramienta', 'bodegaHerramienta']);
            Log::info('Updated PrestamoHerramienta', ['id' => $prestamoHerramienta->id]);
            return response()->json($prestamoHerramienta);
        } catch (\Exception $e) {
            Log::error('Failed to update PrestamoHerramienta', ['id' => $prestamoHerramienta->id, 'error' => $e->getMessage(), 'data' => $request->all()]);
            return response()->json(['error' => 'Failed to update PrestamoHerramienta: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(PrestamoHerramienta $prestamoHerramienta): JsonResponse
    {
        try {
            Log::info('Deleting PrestamoHerramienta', ['id' => $prestamoHerramienta->id]);
            $prestamoHerramienta->delete();
            Log::info('Deleted PrestamoHerramienta', ['id' => $prestamoHerramienta->id]);
            return response()->json(null, 204);
        } catch (\Exception $e) {
            Log::error('Failed to delete PrestamoHerramienta', ['id' => $prestamoHerramienta->id, 'error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to delete PrestamoHerramienta: ' . $e->getMessage()], 500);
        }
    }
}