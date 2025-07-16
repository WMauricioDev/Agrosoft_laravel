<?php

namespace App\Http\Controllers\IoT;

use App\Http\Controllers\Controller;
use App\Http\Requests\IoT\StoreTipoSensorRequest;
use App\Http\Requests\IoT\UpdateTipoSensorRequest;
use App\Models\IoT\TipoSensor;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TipoSensorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $tipoSensores = TipoSensor::all()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'nombre' => $item->nombre,
                    'unidad_medida' => $item->unidad_medida,
                    'medida_minima' => $item->medida_minima,
                    'medida_maxima' => $item->medida_maxima,
                    'descripcion' => $item->descripcion,
                ];
            });

            Log::info('Fetched all TipoSensor records', ['count' => count($tipoSensores)]);
            return response()->json($tipoSensores);
        } catch (\Exception $e) {
            Log::error('Failed to fetch TipoSensor records', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Error al obtener los tipos de sensores: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTipoSensorRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            Log::info('Validated TipoSensor data', ['data' => $validated]);

            $data = [
                'nombre' => $validated['nombre'],
                'unidad_medida' => $validated['unidad_medida'],
                'medida_minima' => $validated['medida_minima'],
                'medida_maxima' => $validated['medida_maxima'],
                'descripcion' => $validated['descripcion'] ?? null,
            ];

            $tipoSensor = DB::transaction(function () use ($data) {
                return TipoSensor::create($data);
            });

            $response = [
                'id' => $tipoSensor->id,
                'nombre' => $tipoSensor->nombre,
                'unidad_medida' => $tipoSensor->unidad_medida,
                'medida_minima' => $tipoSensor->medida_minima,
                'medida_maxima' => $tipoSensor->medida_maxima,
                'descripcion' => $tipoSensor->descripcion,
            ];

            Log::info('Created TipoSensor', ['id' => $tipoSensor->id]);
            return response()->json($response, 201);
        } catch (\Exception $e) {
            Log::error('Failed to create TipoSensor', [
                'error' => $e->getMessage(),
                'data' => $request->all(),
            ]);
            return response()->json(['error' => 'Error al crear tipo de sensor: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(TipoSensor $tipoSensor): JsonResponse
    {
        try {
            Log::info('Fetched TipoSensor', ['id' => $tipoSensor->id]);
            $response = [
                'id' => $tipoSensor->id,
                'nombre' => $tipoSensor->nombre,
                'unidad_medida' => $tipoSensor->unidad_medida,
                'medida_minima' => $tipoSensor->medida_minima,
                'medida_maxima' => $tipoSensor->medida_maxima,
                'descripcion' => $tipoSensor->descripcion,
            ];
            return response()->json($response);
        } catch (\Exception $e) {
            Log::error('Failed to fetch TipoSensor', [
                'id' => $tipoSensor->id,
                'error' => $e->getMessage(),
            ]);
            return response()->json(['error' => 'Error al obtener tipo de sensor: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTipoSensorRequest $request, TipoSensor $tipoSensor): JsonResponse
    {
        try {
            $validated = $request->validated();
            Log::info('Validated TipoSensor update data', ['id' => $tipoSensor->id, 'data' => $validated]);

            $data = [
                'nombre' => $validated['nombre'] ?? $tipoSensor->nombre,
                'unidad_medida' => $validated['unidad_medida'] ?? $tipoSensor->unidad_medida,
                'medida_minima' => $validated['medida_minima'] ?? $tipoSensor->medida_minima,
                'medida_maxima' => $validated['medida_maxima'] ?? $tipoSensor->medida_maxima,
                'descripcion' => $validated['descripcion'] ?? $tipoSensor->descripcion,
            ];

            $tipoSensor->update($data);

            $response = [
                'id' => $tipoSensor->id,
                'nombre' => $tipoSensor->nombre,
                'unidad_medida' => $tipoSensor->unidad_medida,
                'medida_minima' => $tipoSensor->medida_minima,
                'medida_maxima' => $tipoSensor->medida_maxima,
                'descripcion' => $tipoSensor->descripcion,
            ];

            Log::info('Updated TipoSensor', ['id' => $tipoSensor->id]);
            return response()->json($response);
        } catch (\Exception $e) {
            Log::error('Failed to update TipoSensor', [
                'id' => $tipoSensor->id,
                'error' => $e->getMessage(),
                'data' => $request->all(),
            ]);
            return response()->json(['error' => 'Error al actualizar tipo de sensor: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TipoSensor $tipoSensor): JsonResponse
    {
        try {
            Log::info('Deleting TipoSensor', ['id' => $tipoSensor->id]);
            $tipoSensor->delete();
            Log::info('Deleted TipoSensor', ['id' => $tipoSensor->id]);
            return response()->json(null, 204);
        } catch (\Exception $e) {
            Log::error('Failed to delete TipoSensor', [
                'id' => $tipoSensor->id,
                'error' => $e->getMessage(),
            ]);
            return response()->json(['error' => 'Error al eliminar tipo de sensor: ' . $e->getMessage()], 500);
        }
    }
}