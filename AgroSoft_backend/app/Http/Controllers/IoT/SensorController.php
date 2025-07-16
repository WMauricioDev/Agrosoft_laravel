<?php

namespace App\Http\Controllers\IoT;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\IoT\Sensor;
use App\Http\Requests\IoT\StoreSensorRequest;
use App\Http\Requests\IoT\UpdateSensorRequest;

class SensorController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $sensores = Sensor::with(['tipoSensor', 'bancal'])->get()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'nombre' => $item->nombre,
                    'tipo_sensor_id' => $item->tipo_sensor_id,
                    'descripcion' => $item->descripcion,
                    'bancal_id' => $item->bancal_id,
                    'medida_minima' => $item->medida_minima,
                    'medida_maxima' => $item->medida_maxima,
                    'estado' => $item->estado,
                    'device_code' => $item->device_code,
                ];
            });

            Log::info('Fetched all Sensor records', ['count' => count($sensores)]);
            return response()->json($sensores);
        } catch (\Exception $e) {
            Log::error('Failed to fetch Sensor records', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Error al obtener los registros de Sensor: ' . $e->getMessage()], 500);
        }
    }

    public function store(StoreSensorRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            Log::info('Validated Sensor data', ['data' => $validated]);

            $data = [
                'nombre' => $validated['nombre'],
                'tipo_sensor_id' => $validated['tipo_sensor_id'],
                'descripcion' => $validated['descripcion'] ?? '',
                'bancal_id' => $validated['bancal_id'] ?? null,
                'medida_minima' => $validated['medida_minima'] ?? 0,
                'medida_maxima' => $validated['medida_maxima'] ?? 0,
                'estado' => $validated['estado'] ?? 'inactivo',
                'device_code' => $validated['device_code'] ?? null,
            ];

            $sensor = DB::transaction(function () use ($data) {
                return Sensor::create($data);
            });

            $response = [
                'id' => $sensor->id,
                'nombre' => $sensor->nombre,
                'tipo_sensor_id' => $sensor->tipo_sensor_id,
                'descripcion' => $sensor->descripcion,
                'bancal_id' => $sensor->bancal_id,
                'medida_minima' => $sensor->medida_minima,
                'medida_maxima' => $sensor->medida_maxima,
                'estado' => $sensor->estado,
                'device_code' => $sensor->device_code,
            ];

            Log::info('Created Sensor', ['id' => $sensor->id]);
            return response()->json($response, 201);
        } catch (\Exception $e) {
            Log::error('Failed to create Sensor', [
                'error' => $e->getMessage(),
                'data' => $request->all(),
            ]);
            return response()->json(['error' => 'Error al crear Sensor: ' . $e->getMessage()], 500);
        }
    }

    public function show(Sensor $sensor): JsonResponse
    {
        try {
            Log::info('Fetched Sensor', ['id' => $sensor->id]);
            $response = [
                'id' => $sensor->id,
                'nombre' => $sensor->nombre,
                'tipo_sensor_id' => $sensor->tipo_sensor_id,
                'descripcion' => $sensor->descripcion,
                'bancal_id' => $sensor->bancal_id,
                'medida_minima' => $sensor->medida_minima,
                'medida_maxima' => $sensor->medida_maxima,
                'estado' => $sensor->estado,
                'device_code' => $sensor->device_code,
            ];
            return response()->json($response);
        } catch (\Exception $e) {
            Log::error('Failed to fetch Sensor', [
                'id' => $sensor->id,
                'error' => $e->getMessage(),
            ]);
            return response()->json(['error' => 'Error al obtener Sensor: ' . $e->getMessage()], 500);
        }
    }

    public function update(UpdateSensorRequest $request, Sensor $sensor): JsonResponse
    {
        try {
            $validated = $request->validated();
            Log::info('Validated Sensor update data', ['id' => $sensor->id, 'data' => $validated]);

            $data = [
                'nombre' => $validated['nombre'],
                'tipo_sensor_id' => $validated['tipo_sensor_id'],
                'descripcion' => $validated['descripcion'] ?? '',
                'bancal_id' => $validated['bancal_id'] ?? null,
                'medida_minima' => $validated['medida_minima'] ?? 0,
                'medida_maxima' => $validated['medida_maxima'] ?? 0,
                'estado' => $validated['estado'] ?? 'inactivo',
                'device_code' => $validated['device_code'] ?? null,
            ];

            $sensor->update($data);

            $response = [
                'id' => $sensor->id,
                'nombre' => $sensor->nombre,
                'tipo_sensor_id' => $sensor->tipo_sensor_id,
                'descripcion' => $sensor->descripcion,
                'bancal_id' => $sensor->bancal_id,
                'medida_minima' => $sensor->medida_minima,
                'medida_maxima' => $sensor->medida_maxima,
                'estado' => $sensor->estado,
                'device_code' => $sensor->device_code,
            ];

            Log::info('Updated Sensor', ['id' => $sensor->id]);
            return response()->json($response);
        } catch (\Exception $e) {
            Log::error('Failed to update Sensor', [
                'id' => $sensor->id,
                'error' => $e->getMessage(),
                'data' => $request->all(),
            ]);
            return response()->json(['error' => 'Error al actualizar Sensor: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(Sensor $sensor): JsonResponse
    {
        try {
            Log::info('Deleting Sensor', ['id' => $sensor->id]);
            $sensor->delete();
            Log::info('Deleted Sensor', ['id' => $sensor->id]);
            return response()->json(null, 204);
        } catch (\Exception $e) {
            Log::error('Failed to delete Sensor', [
                'id' => $sensor->id,
                'error' => $e->getMessage(),
            ]);
            return response()->json(['error' => 'Error al eliminar Sensor: ' . $e->getMessage()], 500);
        }
    }
}