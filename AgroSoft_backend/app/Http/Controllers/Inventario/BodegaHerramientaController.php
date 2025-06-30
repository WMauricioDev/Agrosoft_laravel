<?php

namespace App\Http\Controllers\Inventario;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Inventario\BodegaHerramienta;
use App\Models\Inventario\Herramienta;
use App\Http\Controllers\Controller;

class BodegaHerramientaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $bodegaHerramientas = BodegaHerramienta::with(['herramienta'])->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'bodega' => $item->bodega_id,
                'herramienta' => $item->herramienta_id,
                'cantidad' => $item->cantidad,
                'costo_total' => $item->costo_total ?? 0,
                'cantidad_prestada' => $item->cantidad_prestada ?? 0,
                'creador' => $item->creador_id,
            ];
        });

        Log::info('Fetched all BodegaHerramienta records', ['count' => count($bodegaHerramientas)]);
        return response()->json($bodegaHerramientas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'bodega' => 'required|exists:bodegas,id',
                'herramienta' => 'required|exists:herramientas,id',
                'cantidad' => 'required|integer|min:1',
                'creador' => 'nullable|exists:users,id',
                'cantidad_prestada' => 'nullable|integer|min:0',
            ]);

            // Obtener el precio de la herramienta
            $herramienta = Herramienta::findOrFail($validated['herramienta']);
            $precio = $herramienta->precio ?? 0; // Usar 0 si precio es null

            // Map frontend field names to model field names
            $data = [
                'bodega_id' => $validated['bodega'],
                'herramienta_id' => $validated['herramienta'],
                'cantidad' => $validated['cantidad'],
                'creador_id' => $validated['creador'] ?? null,
                'cantidad_prestada' => $validated['cantidad_prestada'] ?? 0,
                'costo_total' => $precio * $validated['cantidad'],
            ];

            Log::info('Validated BodegaHerramienta data', ['data' => $data]);

            $bodegaHerramienta = DB::transaction(function () use ($data) {
                return BodegaHerramienta::create($data);
            });

            // Formatear la respuesta para el frontend
            $response = [
                'id' => $bodegaHerramienta->id,
                'bodega' => $bodegaHerramienta->bodega_id,
                'herramienta' => $bodegaHerramienta->herramienta_id,
                'cantidad' => $bodegaHerramienta->cantidad,
                'costo_total' => $bodegaHerramienta->costo_total ?? 0,
                'cantidad_prestada' => $bodegaHerramienta->cantidad_prestada ?? 0,
                'creador' => $bodegaHerramienta->creador_id,
            ];

            Log::info('Created BodegaHerramienta', ['id' => $bodegaHerramienta->id]);
            return response()->json($response, 201);
        } catch (\Exception $e) {
            Log::error('Failed to create BodegaHerramienta', [
                'error' => $e->getMessage(),
                'data' => $request->all(),
            ]);
            return response()->json(['error' => 'Failed to create BodegaHerramienta: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(BodegaHerramienta $bodegaHerramienta): JsonResponse
    {
        Log::info('Fetched BodegaHerramienta', ['id' => $bodegaHerramienta->id]);
        $response = [
            'id' => $bodegaHerramienta->id,
            'bodega' => $bodegaHerramienta->bodega_id,
            'herramienta' => $bodegaHerramienta->herramienta_id,
            'cantidad' => $bodegaHerramienta->cantidad,
            'costo_total' => $bodegaHerramienta->costo_total ?? 0,
            'cantidad_prestada' => $bodegaHerramienta->cantidad_prestada ?? 0,
            'creador' => $bodegaHerramienta->creador_id,
        ];
        return response()->json($response);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BodegaHerramienta $bodegaHerramienta): JsonResponse
    {
        try {
            $validated = $request->validate([
                'bodega' => 'required|exists:bodegas,id',
                'herramienta' => 'required|exists:herramientas,id',
                'cantidad' => 'required|integer|min:1',
                'creador' => 'nullable|exists:users,id',
                'cantidad_prestada' => 'nullable|integer|min:0',
                'costo_total' => 'nullable|numeric|min:0', // Permitir que el frontend envíe costo_total
            ]);

            // Obtener el precio de la herramienta
            $herramienta = Herramienta::findOrFail($validated['herramienta']);
            $precio = $herramienta->precio ?? 0; // Usar 0 si precio es null

            // Map frontend field names to model field names
            $data = [
                'bodega_id' => $validated['bodega'],
                'herramienta_id' => $validated['herramienta'],
                'cantidad' => $validated['cantidad'],
                'creador_id' => $validated['creador'] ?? null,
                'cantidad_prestada' => $validated['cantidad_prestada'] ?? 0,
                'costo_total' => $validated['costo_total'] ?? ($precio * $validated['cantidad']),
            ];

            Log::info('Validated BodegaHerramienta update data', ['id' => $bodegaHerramienta->id, 'data' => $data]);

            $bodegaHerramienta->update($data);

            // Formatear la respuesta para el frontend
            $response = [
                'id' => $bodegaHerramienta->id,
                'bodega' => $bodegaHerramienta->bodega_id,
                'herramienta' => $bodegaHerramienta->herramienta_id,
                'cantidad' => $bodegaHerramienta->cantidad,
                'costo_total' => $bodegaHerramienta->costo_total ?? 0,
                'cantidad_prestada' => $bodegaHerramienta->cantidad_prestada ?? 0,
                'creador' => $bodegaHerramienta->creador_id,
            ];

            Log::info('Updated BodegaHerramienta', ['id' => $bodegaHerramienta->id]);
            return response()->json($response);
        } catch (\Exception $e) {
            Log::error('Failed to update BodegaHerramienta', [
                'id' => $bodegaHerramienta->id,
                'error' => $e->getMessage(),
                'data' => $request->all(),
            ]);
            return response()->json(['error' => 'Failed to update BodegaHerramienta: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BodegaHerramienta $bodegaHerramienta): JsonResponse
    {
        try {
            Log::info('Deleting BodegaHerramienta', ['id' => $bodegaHerramienta->id]);
            $bodegaHerramienta->delete();
            Log::info('Deleted BodegaHerramienta', ['id' => $bodegaHerramienta->id]);
            return response()->json(null, 204);
        } catch (\Exception $e) {
            Log::error('Failed to delete BodegaHerramienta', [
                'id' => $bodegaHerramienta->id,
                'error' => $e->getMessage(),
            ]);
            return response()->json(['error' => 'Failed to delete BodegaHerramienta: ' . $e->getMessage()], 500);
        }
    }
}