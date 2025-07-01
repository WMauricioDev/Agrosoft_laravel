<?php

namespace App\Http\Controllers\Trazabilidad;

use App\Models\Inventario\Insumo;
use App\Models\Inventario\BodegaHerramienta;
use App\Models\Trazabilidad\PrestamoInsumo;
use App\Models\Trazabilidad\PrestamoHerramienta;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Trazabilidad\Actividades;
class ActividadesController extends Controller
{
    public function index(): JsonResponse
    {
        $actividades = Actividades::with(['tipoActividad', 'cultivo', 'usuarios', 'prestamosInsumos.insumo', 'prestamosHerramientas.herramienta'])->orderBy('fecha_fin', 'desc')->get();
        Log::info('Fetched all Actividad records', ['count' => $actividades->count()]);
        return response()->json($actividades);
    }

    public function store(Request $request): JsonResponse
    {
        try {
            Log::info('Payload recibido en store', ['data' => $request->all()]);
            $validated = $request->validate([
                'tipo_actividad_id' => 'required|exists:tipo_actividades,id',
                'descripcion' => 'required|string',
                'fecha_inicio' => 'required|date',
                'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
                'cultivo_id' => 'required|exists:cultivos,id',
                'estado' => 'required|in:PENDIENTE,EN_PROCESO,COMPLETADA,CANCELADA',
                'prioridad' => 'required|in:ALTA,MEDIA,BAJA',
                'instrucciones_adicionales' => 'nullable|string',
                'usuarios' => 'required|array',
                'usuarios.*' => 'exists:users,id',
                'insumos' => 'nullable|array',
                'insumos.*.insumo_id' => 'required|exists:insumos,id',
                'insumos.*.cantidad_usada' => 'required|integer|min:0',
                'herramientas' => 'nullable|array',
                'herramientas.*.herramienta_id' => 'required|exists:herramientas,id',
                'herramientas.*.cantidad_entregada' => 'required|integer|min:1',
                'herramientas.*.entregada' => 'nullable|boolean',
                'herramientas.*.devuelta' => 'nullable|boolean',
                'herramientas.*.fecha_devolucion' => 'nullable|date',
            ]);

            Log::info('Validated Actividad data', ['data' => $validated]);

            $actividad = DB::transaction(function () use ($validated) {
                $actividad = Actividades::create([
                    'tipo_actividad_id' => $validated['tipo_actividad_id'],
                    'descripcion' => $validated['descripcion'],
                    'fecha_inicio' => $validated['fecha_inicio'],
                    'fecha_fin' => $validated['fecha_fin'],
                    'cultivo_id' => $validated['cultivo_id'],
                    'estado' => $validated['estado'],
                    'prioridad' => $validated['prioridad'],
                    'instrucciones_adicionales' => $validated['instrucciones_adicionales'],
                ]);

                $actividad->usuarios()->sync($validated['usuarios']);

                if (!empty($validated['insumos'])) {
                    foreach ($validated['insumos'] as $insumoData) {
                        $insumo = Insumo::findOrFail($insumoData['insumo_id']);
                        if ($insumo->cantidad < $insumoData['cantidad_usada']) {
                            throw new \Exception("No hay suficiente stock para {$insumo->nombre}. Disponible: {$insumo->cantidad}, solicitado: {$insumoData['cantidad_usada']}");
                        }
                        $insumo->cantidad -= $insumoData['cantidad_usada'];
                        $insumo->save();

                        PrestamoInsumo::create([
                            'actividad_id' => $actividad->id,
                            'insumo_id' => $insumoData['insumo_id'],
                            'cantidad_usada' => $insumoData['cantidad_usada'],
                            'unidad_medida_id' => $insumo->unidad_medida_id,
                        ]);
                    }
                }

                if (!empty($validated['herramientas'])) {
                    foreach ($validated['herramientas'] as $herramientaData) {
                        $bodegaHerramienta = BodegaHerramienta::where('herramienta_id', $herramientaData['herramienta_id'])->first();
                        if (!$bodegaHerramienta || $bodegaHerramienta->cantidad < $herramientaData['cantidad_entregada']) {
                            $disponible = $bodegaHerramienta ? $bodegaHerramienta->cantidad : 0;
                            throw new \Exception("No hay suficientes herramientas disponibles. Disponible: {$disponible}, solicitado: {$herramientaData['cantidad_entregada']}");
                        }
                        $bodegaHerramienta->cantidad -= $herramientaData['cantidad_entregada'];
                        $bodegaHerramienta->cantidad_prestada += $herramientaData['cantidad_entregada'];
                        $bodegaHerramienta->save();

                        PrestamoHerramienta::create([
                            'actividad_id' => $actividad->id,
                            'herramienta_id' => $herramientaData['herramienta_id'],
                            'bodega_herramienta_id' => $bodegaHerramienta->id,
                            'cantidad_entregada' => $herramientaData['cantidad_entregada'],
                            'cantidad_devuelta' => 0,
                            'entregada' => $herramientaData['entregada'] ?? true,
                            'devuelta' => $herramientaData['devuelta'] ?? false,
                            'fecha_devolucion' => $herramientaData['fecha_devolucion'] ?? null,
                        ]);
                    }
                }

                return $actividad;
            });

            $actividad->load(['tipoActividad', 'cultivo', 'usuarios', 'prestamosInsumos.insumo', 'prestamosHerramientas.herramienta']);
            Log::info('Created Actividad', ['id' => $actividad->id]);
            return response()->json($actividad, 201);
        } catch (\Exception $e) {
            Log::error('Failed to create Actividad', ['error' => $e->getMessage(), 'data' => $request->all()]);
            return response()->json(['error' => 'Failed to create Actividad: ' . $e->getMessage()], 500);
        }
    }

    public function show(Actividades $actividad): JsonResponse
    {
        Log::info('Fetched Actividad', ['id' => $actividad->id]);
        $actividad->load(['tipoActividad', 'cultivo', 'usuarios', 'prestamosInsumos.insumo', 'prestamosHerramientas.herramienta']);
        return response()->json($actividad);
    }

    public function update(Request $request, Actividades $actividad): JsonResponse
    {
        try {
            $validated = $request->validate([
                'tipo_actividad_id' => 'required|exists:tipo_actividades,id',
                'descripcion' => 'required|string',
                'fecha_inicio' => 'required|date',
                'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
                'cultivo_id' => 'required|exists:cultivos,id',
                'estado' => 'required|in:PENDIENTE,EN_PROCESO,COMPLETADA,CANCELADA',
                'prioridad' => 'required|in:ALTA,MEDIA,BAJA',
                'instrucciones_adicionales' => 'nullable|string',
                'usuarios' => 'nullable|array',
                'usuarios.*' => 'exists:users,id',
                'insumos' => 'nullable|array',
                'insumos.*.insumo_id' => 'required|exists:insumos,id',
                'insumos.*.cantidad_usada' => 'required|integer|min:0',
                'herramientas' => 'nullable|array',
                'herramientas.*.herramienta_id' => 'required|exists:herramientas,id',
                'herramientas.*.cantidad_entregada' => 'required|integer|min:1',
                'herramientas.*.entregada' => 'nullable|boolean',
                'herramientas.*.devuelta' => 'nullable|boolean',
                'herramientas.*.fecha_devolucion' => 'nullable|date',
            ]);

            Log::info('Validated Actividad update data', ['id' => $actividad->id, 'data' => $validated]);

            $actividad = DB::transaction(function () use ($actividad, $validated) {
                $actividad->update([
                    'tipo_actividad_id' => $validated['tipo_actividad_id'],
                    'descripcion' => $validated['descripcion'],
                    'fecha_inicio' => $validated['fecha_inicio'],
                    'fecha_fin' => $validated['fecha_fin'],
                    'cultivo_id' => $validated['cultivo_id'],
                    'estado' => $validated['estado'],
                    'prioridad' => $validated['prioridad'],
                    'instrucciones_adicionales' => $validated['instrucciones_adicionales'],
                ]);

                if (isset($validated['usuarios'])) {
                    $actividad->usuarios()->sync($validated['usuarios']);
                }

                if (isset($validated['insumos'])) {
                    foreach ($actividad->prestamosInsumos as $prestamo) {
                        $insumo = Insumo::findOrFail($prestamo->insumo_id);
                        $insumo->cantidad += $prestamo->cantidad_usada;
                        $insumo->save();
                    }
                    $actividad->prestamosInsumos()->delete();

                    foreach ($validated['insumos'] as $insumoData) {
                        $insumo = Insumo::findOrFail($insumoData['insumo_id']);
                        if ($insumo->cantidad < $insumoData['cantidad_usada']) {
                            throw new \Exception("No hay suficiente stock para {$insumo->nombre}. Disponible: {$insumo->cantidad}, solicitado: {$insumoData['cantidad_usada']}");
                        }
                        $insumo->cantidad -= $insumoData['cantidad_usada'];
                        $insumo->save();

                        PrestamoInsumo::create([
                            'actividad_id' => $actividad->id,
                            'insumo_id' => $insumoData['insumo_id'],
                            'cantidad_usada' => $insumoData['cantidad_usada'],
                            'unidad_medida_id' => $insumo->unidad_medida_id,
                        ]);
                    }
                }

                if (isset($validated['herramientas'])) {
                    foreach ($actividad->prestamosHerramientas as $prestamo) {
                        if ($prestamo->bodegaHerramienta && !$prestamo->devuelta) {
                            $prestamo->bodegaHerramienta->cantidad += $prestamo->cantidad_entregada;
                            $prestamo->bodegaHerramienta->cantidad_prestada -= $prestamo->cantidad_entregada;
                            $prestamo->bodegaHerramienta->save();
                        }
                    }
                    $actividad->prestamosHerramientas()->delete();

                    foreach ($validated['herramientas'] as $herramientaData) {
                        $bodegaHerramienta = BodegaHerramienta::where('herramienta_id', $herramientaData['herramienta_id'])->first();
                        if (!$bodegaHerramienta || $bodegaHerramienta->cantidad < $herramientaData['cantidad_entregada']) {
                            $disponible = $bodegaHerramienta ? $bodegaHerramienta->cantidad : 0;
                            throw new \Exception("No hay suficientes herramientas disponibles. Disponible: {$disponible}, solicitado: {$herramientaData['cantidad_entregada']}");
                        }
                        $bodegaHerramienta->cantidad -= $herramientaData['cantidad_entregada'];
                        $bodegaHerramienta->cantidad_prestada += $herramientaData['cantidad_entregada'];
                        $bodegaHerramienta->save();

                        PrestamoHerramienta::create([
                            'actividad_id' => $actividad->id,
                            'herramienta_id' => $herramientaData['herramienta_id'],
                            'bodega_herramienta_id' => $bodegaHerramienta->id,
                            'cantidad_entregada' => $herramientaData['cantidad_entregada'],
                            'cantidad_devuelta' => 0,
                            'entregada' => $herramientaData['entregada'] ?? true,
                            'devuelta' => $herramientaData['devuelta'] ?? false,
                            'fecha_devolucion' => $herramientaData['fecha_devolucion'] ?? null,
                        ]);
                    }
                }

                return $actividad;
            });

            $actividad->load(['tipoActividad', 'cultivo', 'usuarios', 'prestamosInsumos.insumo', 'prestamosHerramientas.herramienta']);
            Log::info('Updated Actividad', ['id' => $actividad->id]);
            return response()->json($actividad);
        } catch (\Exception $e) {
            Log::error('Failed to update Actividad', ['id' => $actividad->id, 'error' => $e->getMessage(), 'data' => $request->all()]);
            return response()->json(['error' => 'Failed to update Actividad: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(Actividades $actividad): JsonResponse
    {
        try {
            Log::info('Deleting Actividad', ['id' => $actividad->id]);
            $actividad->delete();
            Log::info('Deleted Actividad', ['id' => $actividad->id]);
            return response()->json(null, 204);
        } catch (\Exception $e) {
            Log::error('Failed to delete Actividad', ['id' => $actividad->id, 'error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to delete Actividad: ' . $e->getMessage()], 500);
        }
    }

    public function finalizar(Request $request, Actividades $actividad): JsonResponse
    {
        try {
            if ($actividad->estado === 'COMPLETADA') {
                return response()->json(['error' => 'Esta actividad ya está completada'], 400);
            }

            $validated = $request->validate([
                'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            ]);

            Log::info('Validated Finalizar Actividad data', ['id' => $actividad->id, 'data' => $validated]);

            $actividad = DB::transaction(function () use ($actividad, $validated) {
                $actividad->update([
                    'fecha_fin' => $validated['fecha_fin'],
                    'estado' => 'COMPLETADA',
                ]);

                $prestamosInsumos = $actividad->prestamosInsumos()->where('cantidad_devuelta', '<', DB::raw('cantidad_usada'))->get();
                foreach ($prestamosInsumos as $prestamo) {
                    $prestamo->update([
                        'cantidad_devuelta' => $prestamo->cantidad_usada,
                        'fecha_devolucion' => $validated['fecha_fin'],
                    ]);
                }

                $prestamosHerramientas = $actividad->prestamosHerramientas()->where('devuelta', false)->get();
                foreach ($prestamosHerramientas as $prestamo) {
                    if ($prestamo->bodegaHerramienta) {
                        $cantidad_devuelta = $prestamo->cantidad_entregada - $prestamo->cantidad_devuelta;
                        $prestamo->bodegaHerramienta->cantidad += $cantidad_devuelta;
                        $prestamo->bodegaHerramienta->cantidad_prestada -= $cantidad_devuelta;
                        $prestamo->bodegaHerramienta->save();
                    }
                    $prestamo->update([
                        'cantidad_devuelta' => $prestamo->cantidad_entregada,
                        'devuelta' => true,
                        'fecha_devolucion' => $validated['fecha_fin'],
                    ]);
                }

                return $actividad;
            });

            Log::info('Finalized Actividad', ['id' => $actividad->id]);
            return response()->json([
                'message' => 'Actividad finalizada correctamente',
                'insumos_devueltos' => $actividad->prestamosInsumos()->count(),
                'herramientas_devueltas' => $actividad->prestamosHerramientas()->count(),
            ], 200);
        } catch (\Exception $e) {
            Log::error('Failed to finalize Actividad', ['id' => $actividad->id, 'error' => $e->getMessage(), 'data' => $request->all()]);
            return response()->json(['error' => 'Failed to finalize Actividad: ' . $e->getMessage()], 500);
        }
    }
}