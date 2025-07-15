<?php

namespace App\Http\Controllers\Inventario;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Inventario\Herramienta;
use App\Http\Requests\Inventario\StoreHerramientaRequest;
use App\Http\Requests\Inventario\UpdateHerramientaRequest;

class HerramientaController extends Controller
{
    /**
     * Muestra una lista de todos los recursos.
     */
    public function index(): JsonResponse
    {
        try {
            $herramientas = Herramienta::all()->map(function ($herramienta) {
                return [
                    'id' => $herramienta->id,
                    'nombre' => $herramienta->nombre,
                    'descripcion' => $herramienta->descripcion,
                    'cantidad' => $herramienta->cantidad,
                    'estado' => $herramienta->estado,
                    'activo' => $herramienta->activo,
                    'fecha_registro' => $herramienta->fecha_registro->toISOString(),
                    'precio' => $herramienta->precio,
                ];
            });
            Log::info('Fetched all Herramienta records', ['count' => $herramientas->count()]);
            return response()->json($herramientas);
        } catch (\Exception $e) {
            Log::error('Failed to fetch Herramienta records', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Error al obtener las herramientas: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Almacena un nuevo recurso en la base de datos.
     */
    public function store(StoreHerramientaRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            Log::info('Validated Herramienta data', ['data' => $validated]);

            $data = array_merge(
                $request->only([
                    'nombre',
                    'descripcion',
                    'cantidad',
                    'estado',
                    'activo',
                    'precio',
                ]),
                ['fecha_registro' => $request->fecha_registro ?? now()]
            );

            $herramienta = DB::transaction(function () use ($data) {
                return Herramienta::create($data);
            });

            Log::info('Created Herramienta', ['id' => $herramienta->id, 'nombre' => $herramienta->nombre]);

            return response()->json([
                'mensaje' => 'Herramienta registrada con éxito',
                'herramienta' => [
                    'id' => $herramienta->id,
                    'nombre' => $herramienta->nombre,
                    'descripcion' => $herramienta->descripcion,
                    'cantidad' => $herramienta->cantidad,
                    'estado' => $herramienta->estado,
                    'activo' => $herramienta->activo,
                    'fecha_registro' => $herramienta->fecha_registro->toISOString(),
                    'precio' => $herramienta->precio,
                ],
            ], 201);
        } catch (\Exception $e) {
            Log::error('Failed to create Herramienta', [
                'error' => $e->getMessage(),
                'data' => $request->all(),
            ]);
            return response()->json(['error' => 'Error al crear la herramienta: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Muestra un recurso específico.
     */
    public function show(Herramienta $herramienta): JsonResponse
    {
        try {
            Log::info('Fetched Herramienta', ['id' => $herramienta->id]);
            return response()->json([
                'id' => $herramienta->id,
                'nombre' => $herramienta->nombre,
                'descripcion' => $herramienta->descripcion,
                'cantidad' => $herramienta->cantidad,
                'estado' => $herramienta->estado,
                'activo' => $herramienta->activo,
                'fecha_registro' => $herramienta->fecha_registro->toISOString(),
                'precio' => $herramienta->precio,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to fetch Herramienta', [
                'id' => $herramienta->id,
                'error' => $e->getMessage(),
            ]);
            return response()->json(['error' => 'Error al obtener la herramienta: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Actualiza un recurso específico en la base de datos.
     */
    public function update(UpdateHerramientaRequest $request, Herramienta $herramienta): JsonResponse
    {
        try {
            $validated = $request->validated();
            Log::info('Validated Herramienta update data', ['id' => $herramienta->id, 'data' => $validated]);

            $herramienta->update($request->only([
                'nombre',
                'descripcion',
                'cantidad',
                'estado',
                'activo',
                'precio',
                'fecha_registro',
            ]));

            Log::info('Updated Herramienta', ['id' => $herramienta->id]);

            return response()->json([
                'id' => $herramienta->id,
                'nombre' => $herramienta->nombre,
                'descripcion' => $herramienta->descripcion,
                'cantidad' => $herramienta->cantidad,
                'estado' => $herramienta->estado,
                'activo' => $herramienta->activo,
                'fecha_registro' => $herramienta->fecha_registro->toISOString(),
                'precio' => $herramienta->precio,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update Herramienta', [
                'id' => $herramienta->id,
                'error' => $e->getMessage(),
                'data' => $request->all(),
            ]);
            return response()->json(['error' => 'Error al actualizar la herramienta: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Elimina un recurso específico de la base de datos.
     */
    public function destroy(Herramienta $herramienta): JsonResponse
    {
        try {
            Log::info('Deleting Herramienta', ['id' => $herramienta->id]);
            $herramienta->delete();
            Log::info('Deleted Herramienta', ['id' => $herramienta->id]);
            return response()->json(null, 204);
        } catch (\Exception $e) {
            Log::error('Failed to delete Herramienta', [
                'id' => $herramienta->id,
                'error' => $e->getMessage(),
            ]);
            return response()->json(['error' => 'Error al eliminar la herramienta: ' . $e->getMessage()], 500);
        }
    }
}