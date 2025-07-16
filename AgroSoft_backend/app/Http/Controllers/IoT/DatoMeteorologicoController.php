<?php

namespace App\Http\Controllers\IoT;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\IoT\DatoMeteorologico;
use App\Http\Requests\IoT\StoreDatoMeteorologicoRequest;
use App\Http\Requests\IoT\UpdateDatoMeteorologicoRequest;

class DatoMeteorologicoController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $datosMeteorologicos = DatoMeteorologico::with(['sensor', 'bancal'])->get()->map(function ($item) {
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
                    'fecha_medicion' => $item->fecha_medicion,
                ];
            });

            Log::info('Fetched all DatoMeteorologico records', ['count' => count($datosMeteorologicos)]);
            return response()->json($datosMeteorologicos);
        } catch (\Exception $e) {
            Log::error('Failed to fetch DatoMeteorologico records', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Error al obtener los registros de DatoMeteorologico: ' . $e->getMessage()], 500);
        }
    }

    public function store(StoreDatoMeteorologicoRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            Log::info('Validated DatoMeteorologico data', ['data' => $validated]);

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
                'fecha_medicion' => $validated['fecha_medicion'] ?? now(),
            ];

            $datoMeteorologico = DB::transaction(function () use ($data) {
                return DatoMeteorologico::create($data);
            });

            $response = [
                'id' => $datoMeteorologico->id,
                'sensor_id' => $datoMeteorologico->sensor_id,
                'bancal_id' => $datoMeteorologico->bancal_id,
                'temperatura' => $datoMeteorologico->temperatura,
                'humedad_ambiente' => $datoMeteorologico->humedad_ambiente,
                'luminosidad' => $datoMeteorologico->luminosidad,
                'lluvia' => $datoMeteorologico->lluvia,
                'velocidad_viento' => $datoMeteorologico->velocidad_viento,
                'direccion_viento' => $datoMeteorologico->direccion_viento,
                'humedad_suelo' => $datoMeteorologico->humedad_suelo,
                'ph_suelo' => $datoMeteorologico->ph_suelo,
                'fecha_medicion' => $datoMeteorologico->fecha_medicion,
            ];

            Log::info('Created DatoMeteorologico', ['id' => $datoMeteorologico->id]);
            return response()->json($response, 201);
        } catch (\Exception $e) {
            Log::error('Failed to create DatoMeteorologico', [
                'error' => $e->getMessage(),
                'data' => $request->all(),
            ]);
            return response()->json(['error' => 'Error al crear DatoMeteorologico: ' . $e->getMessage()], 500);
        }
    }

    public function show(DatoMeteorologico $datoMeteorologico): JsonResponse
    {
        try {
            Log::info('Fetched DatoMeteorologico', ['id' => $datoMeteorologico->id]);
            $response = [
                'id' => $datoMeteorologico->id,
                'sensor_id' => $datoMeteorologico->sensor_id,
                'bancal_id' => $datoMeteorologico->bancal_id,
                'temperatura' => $datoMeteorologico->temperatura,
                'humedad_ambiente' => $datoMeteorologico->humedad_ambiente,
                'luminosidad' => $datoMeteorologico->luminosidad,
                'lluvia' => $datoMeteorologico->lluvia,
                'velocidad_viento' => $datoMeteorologico->velocidad_viento,
                'direccion_viento' => $datoMeteorologico->direccion_viento,
                'humedad_suelo' => $datoMeteorologico->humedad_suelo,
                'ph_suelo' => $datoMeteorologico->ph_suelo,
                'fecha_medicion' => $datoMeteorologico->fecha_medicion,
            ];
            return response()->json($response);
        } catch (\Exception $e) {
            Log::error('Failed to fetch DatoMeteorologico', [
                'id' => $datoMeteorologico->id,
                'error' => $e->getMessage(),
            ]);
            return response()->json(['error' => 'Error al obtener DatoMeteorologico: ' . $e->getMessage()], 500);
        }
    }

    public function update(UpdateDatoMeteorologicoRequest $request, DatoMeteorologico $datoMeteorologico): JsonResponse
    {
        try {
            $validated = $request->validated();
            Log::info('Validated DatoMeteorologico update data', ['id' => $datoMeteorologico->id, 'data' => $validated]);

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
                'fecha_medicion' => $validated['fecha_medicion'] ?? $datoMeteorologico->fecha_medicion,
            ];

            $datoMeteorologico->update($data);

            $response = [
                'id' => $datoMeteorologico->id,
                'sensor_id' => $datoMeteorologico->sensor_id,
                'bancal_id' => $datoMeteorologico->bancal_id,
                'temperatura' => $datoMeteorologico->temperatura,
                'humedad_ambiente' => $datoMeteorologico->humedad_ambiente,
                'luminosidad' => $datoMeteorologico->luminosidad,
                'lluvia' => $datoMeteorologico->lluvia,
                'velocidad_viento' => $datoMeteorologico->velocidad_viento,
                'direccion_viento' => $datoMeteorologico->direccion_viento,
                'humedad_suelo' => $datoMeteorologico->humedad_suelo,
                'ph_suelo' => $datoMeteorologico->ph_suelo,
                'fecha_medicion' => $datoMeteorologico->fecha_medicion,
            ];

            Log::info('Updated DatoMeteorologico', ['id' => $datoMeteorologico->id]);
            return response()->json($response);
        } catch (\Exception $e) {
            Log::error('Failed to update DatoMeteorologico', [
                'id' => $datoMeteorologico->id,
                'error' => $e->getMessage(),
                'data' => $request->all(),
            ]);
            return response()->json(['error' => 'Error al actualizar DatoMeteorologico: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(DatoMeteorologico $datoMeteorologico): JsonResponse
    {
        try {
            Log::info('Deleting DatoMeteorologico', ['id' => $datoMeteorologico->id]);
            $datoMeteorologico->delete();
            Log::info('Deleted DatoMeteorologico', ['id' => $datoMeteorologico->id]);
            return response()->json(null, 204);
        } catch (\Exception $e) {
            Log::error('Failed to delete DatoMeteorologico', [
                'id' => $datoMeteorologico->id,
                'error' => $e->getMessage(),
            ]);
            return response()->json(['error' => 'Error al eliminar DatoMeteorologico: ' . $e->getMessage()], 500);
        }
    }
}