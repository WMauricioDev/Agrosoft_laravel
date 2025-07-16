<?php

namespace App\Http\Controllers\IoT;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\IoT\DatoHistorico;
use App\Http\Requests\IoT\StoreDatoHistoricoRequest;
use App\Http\Requests\IoT\UpdateDatoHistoricoRequest;

class DatoHistoricoController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $datosHistoricos = DatoHistorico::with(['sensor', 'bancal'])->get()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'sensor_id' => $item->sensor_id,
                    'bancal_id' => $item->bancal_id,
                    'temperatura' => $item->temperatura,
                    'humedad_ambiente' => $item->humedad_ambiente,
                    'luminosidad' => $item->luminosidad,
                    'lluvia' => $item->lluvia,
                    'velocidad_viento' => $item->velocidad_viento,
                    'direccion_viento' => $item->direccion_viento,
                    'humedad_suelo' => $item->humedad_suelo,
                    'ph_suelo' => $item->ph_suelo,
                    'fecha_promedio' => $item->fecha_promedio,
                    'cantidad_mediciones' => $item->cantidad_mediciones,
                ];
            });

            Log::info('Fetched all DatoHistorico records', ['count' => count($datosHistoricos)]);
            return response()->json($datosHistoricos);
        } catch (\Exception $e) {
            Log::error('Failed to fetch DatoHistorico records', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Error al obtener los registros de DatoHistorico: ' . $e->getMessage()], 500);
        }
    }

    public function store(StoreDatoHistoricoRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            Log::info('Validated DatoHistorico data', ['data' => $validated]);

            $data = [
                'sensor_id' => $validated['sensor_id'] ?? null,
                'bancal_id' => $validated['bancal_id'] ?? null,
                'temperatura' => $validated['temperatura'] ?? null,
                'humedad_ambiente' => $validated['humedad_ambiente'] ?? null,
                'luminosidad' => $validated['luminosidad'] ?? null,
                'lluvia' => $validated['lluvia'] ?? null,
                'velocidad_viento' => $validated['velocidad_viento'] ?? null,
                'direccion_viento' => $validated['direccion_viento'] ?? null,
                'humedad_suelo' => $validated['humedad_suelo'] ?? null,
                'ph_suelo' => $validated['ph_suelo'] ?? null,
                'fecha_promedio' => $validated['fecha_promedio'],
                'cantidad_mediciones' => $validated['cantidad_mediciones'] ?? 0,
            ];

            $datoHistorico = DB::transaction(function () use ($data) {
                return DatoHistorico::create($data);
            });

            $response = [
                'id' => $datoHistorico->id,
                'sensor_id' => $datoHistorico->sensor_id,
                'bancal_id' => $datoHistorico->bancal_id,
                'temperatura' => $datoHistorico->temperatura,
                'humedad_ambiente' => $datoHistorico->humedad_ambiente,
                'luminosidad' => $datoHistorico->luminosidad,
                'lluvia' => $datoHistorico->lluvia,
                'velocidad_viento' => $datoHistorico->velocidad_viento,
                'direccion_viento' => $datoHistorico->direccion_viento,
                'humedad_suelo' => $datoHistorico->humedad_suelo,
                'ph_suelo' => $datoHistorico->ph_suelo,
                'fecha_promedio' => $datoHistorico->fecha_promedio,
                'cantidad_mediciones' => $datoHistorico->cantidad_mediciones,
            ];

            Log::info('Created DatoHistorico', ['id' => $datoHistorico->id]);
            return response()->json($response, 201);
        } catch (\Exception $e) {
            Log::error('Failed to create DatoHistorico', [
                'error' => $e->getMessage(),
                'data' => $request->all(),
            ]);
            return response()->json(['error' => 'Error al crear DatoHistorico: ' . $e->getMessage()], 500);
        }
    }

    public function show(DatoHistorico $datoHistorico): JsonResponse
    {
        try {
            Log::info('Fetched DatoHistorico', ['id' => $datoHistorico->id]);
            $response = [
                'id' => $datoHistorico->id,
                'sensor_id' => $datoHistorico->sensor_id,
                'bancal_id' => $datoHistorico->bancal_id,
                'temperatura' => $datoHistorico->temperatura,
                'humedad_ambiente' => $datoHistorico->humedad_ambiente,
                'luminosidad' => $datoHistorico->luminosidad,
                'lluvia' => $datoHistorico->lluvia,
                'velocidad_viento' => $datoHistorico->velocidad_viento,
                'direccion_viento' => $datoHistorico->direccion_viento,
                'humedad_suelo' => $datoHistorico->humedad_suelo,
                'ph_suelo' => $datoHistorico->ph_suelo,
                'fecha_promedio' => $datoHistorico->fecha_promedio,
                'cantidad_mediciones' => $datoHistorico->cantidad_mediciones,
            ];
            return response()->json($response);
        } catch (\Exception $e) {
            Log::error('Failed to fetch DatoHistorico', [
                'id' => $datoHistorico->id,
                'error' => $e->getMessage(),
            ]);
            return response()->json(['error' => 'Error al obtener DatoHistorico: ' . $e->getMessage()], 500);
        }
    }

    public function update(UpdateDatoHistoricoRequest $request, DatoHistorico $datoHistorico): JsonResponse
    {
        try {
            $validated = $request->validated();
            Log::info('Validated DatoHistorico update data', ['id' => $datoHistorico->id, 'data' => $validated]);

            $data = [
                'sensor_id' => $validated['sensor_id'] ?? null,
                'bancal_id' => $validated['bancal_id'] ?? null,
                'temperatura' => $validated['temperatura'] ?? null,
                'humedad_ambiente' => $validated['humedad_ambiente'] ?? null,
                'luminosidad' => $validated['luminosidad'] ?? null,
                'lluvia' => $validated['lluvia'] ?? null,
                'velocidad_viento' => $validated['velocidad_viento'] ?? null,
                'direccion_viento' => $validated['direccion_viento'] ?? null,
                'humedad_suelo' => $validated['humedad_suelo'] ?? null,
                'ph_suelo' => $validated['ph_suelo'] ?? null,
                'fecha_promedio' => $validated['fecha_promedio'],
                'cantidad_mediciones' => $validated['cantidad_mediciones'] ?? 0,
            ];

            $datoHistorico->update($data);

            $response = [
                'id' => $datoHistorico->id,
                'sensor_id' => $datoHistorico->sensor_id,
                'bancal_id' => $datoHistorico->bancal_id,
                'temperatura' => $datoHistorico->temperatura,
                'humedad_ambiente' => $datoHistorico->humedad_ambiente,
                'luminosidad' => $datoHistorico->luminosidad,
                'lluvia' => $datoHistorico->lluvia,
                'velocidad_viento' => $datoHistorico->velocidad_viento,
                'direccion_viento' => $datoHistorico->direccion_viento,
                'humedad_suelo' => $datoHistorico->humedad_suelo,
                'ph_suelo' => $datoHistorico->ph_suelo,
                'fecha_promedio' => $datoHistorico->fecha_promedio,
                'cantidad_mediciones' => $datoHistorico->cantidad_mediciones,
            ];

            Log::info('Updated DatoHistorico', ['id' => $datoHistorico->id]);
            return response()->json($response);
        } catch (\Exception $e) {
            Log::error('Failed to update DatoHistorico', [
                'id' => $datoHistorico->id,
                'error' => $e->getMessage(),
                'data' => $request->all(),
            ]);
            return response()->json(['error' => 'Error al actualizar DatoHistorico: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(DatoHistorico $datoHistorico): JsonResponse
    {
        try {
            Log::info('Deleting DatoHistorico', ['id' => $datoHistorico->id]);
            $datoHistorico->delete();
            Log::info('Deleted DatoHistorico', ['id' => $datoHistorico->id]);
            return response()->json(null, 204);
        } catch (\Exception $e) {
            Log::error('Failed to delete DatoHistorico', [
                'id' => $datoHistorico->id,
                'error' => $e->getMessage(),
            ]);
            return response()->json(['error' => 'Error al eliminar DatoHistorico: ' . $e->getMessage()], 500);
        }
    }
}