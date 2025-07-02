<?php

namespace App\Http\Controllers\Trazabilidad;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\Trazabilidad\Afeccion;

class AfeccionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        Log::info('Fetching all afecciones');
        $afecciones = Afeccion::with(['plaga', 'cultivo', 'bancal'])->get()->map(function ($afeccion) {
            return $this->formatAfeccion($afeccion);
        });

        return response()->json($afecciones);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        Log::info('Datos recibidos en store:', $request->all());

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'fecha_deteccion' => 'required|date',
            'gravedad' => 'required|in:L,M,G',
            'plaga_id' => 'required|exists:plagas,id',
            'cultivo_id' => 'required|exists:cultivos,id',
            'bancal_id' => 'required|exists:bancals,id', // Corregido: bancales -> bancals
            'reporte' => 'nullable|integer|exists:reportes,id',
        ]);

        if ($validator->fails()) {
            Log::error('Errores de validación en store:', $validator->errors()->toArray());
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->only([
            'nombre',
            'descripcion',
            'fecha_deteccion',
            'gravedad',
            'plaga_id',
            'cultivo_id',
            'bancal_id',
            'reporte',
        ]);

        // Set default estado to 'AC' (Activa) for new afecciones
        $data['estado'] = 'AC';

        try {
            $afeccion = Afeccion::create($data);
            $afeccion->load(['plaga', 'cultivo', 'bancal']);
            return response()->json($this->formatAfeccion($afeccion), 201);
        } catch (\Exception $e) {
            Log::error('Error al crear la afeccion: ' . $e->getMessage());
            return response()->json(['error' => 'Error al crear la afección'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Afeccion $afeccion): JsonResponse
    {
        Log::info('Fetching afeccion with ID: ' . $afeccion->id);
        $afeccion->load(['plaga', 'cultivo', 'bancal']);
        return response()->json($this->formatAfeccion($afeccion));
    }

    /**
     * Update the specified resource in storage (PATCH).
     */
    public function update(Request $request, Afeccion $afeccion): JsonResponse
    {
        Log::info('Datos recibidos en update:', $request->all());

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
        ]);

        if ($validator->fails()) {
            Log::error('Errores de validación en update:', $validator->errors()->toArray());
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->only(['nombre', 'descripcion']);

        try {
            $afeccion->update($data);
            $afeccion->load(['plaga', 'cultivo', 'bancal']);
            return response()->json($this->formatAfeccion($afeccion));
        } catch (\Exception $e) {
            Log::error('Error al actualizar la afeccion: ' . $e->getMessage());
            return response()->json(['error' => 'Error al actualizar la afección'], 500);
        }
    }

    /**
     * Change the estado of the specified afeccion.
     */
    public function cambiarEstado(Request $request, Afeccion $afeccion): JsonResponse
    {
        Log::info('Datos recibidos en cambiar_estado:', $request->all());

        $validator = Validator::make($request->all(), [
            'estado' => 'required|in:ST,EC,EL',
        ]);

        if ($validator->fails()) {
            Log::error('Errores de validación en cambiar_estado:', $validator->errors()->toArray());
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $afeccion->estado = $request->input('estado');
            $afeccion->save();
            $afeccion->load(['plaga', 'cultivo', 'bancal']);
            return response()->json($this->formatAfeccion($afeccion));
        } catch (\Exception $e) {
            Log::error('Error al cambiar estado de la afeccion: ' . $e->getMessage());
            return response()->json(['error' => 'Error al cambiar el estado de la afección'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Afeccion $afeccion): JsonResponse
    {
        Log::info('Eliminando afeccion con ID: ' . $afeccion->id);
        try {
            $afeccion->delete();
            return response()->json(null, 204);
        } catch (\Exception $e) {
            Log::error('Error al eliminar la afeccion: ' . $e->getMessage());
            return response()->json(['error' => 'Error al eliminar la afección'], 500);
        }
    }

    /**
     * Format the afeccion data for consistent response.
     */
    private function formatAfeccion(Afeccion $afeccion): array
    {
        return [
            'id' => $afeccion->id,
            'nombre' => $afeccion->nombre,
            'descripcion' => $afeccion->descripcion,
            'fecha_deteccion' => $afeccion->fecha_deteccion->format('Y-m-d'),
            'gravedad' => $afeccion->gravedad,
            'estado' => $afeccion->estado,
            'reporte' => $afeccion->reporte ? [
                'id' => $afeccion->reporte->id,
                'usuario' => $afeccion->reporte->user ? $afeccion->reporte->user->name : null,
            ] : null,
            'plaga' => $afeccion->plaga ? [
                'id' => $afeccion->plaga->id,
                'nombre' => $afeccion->plaga->nombre,
                'descripcion' => $afeccion->plaga->descripcion,
            ] : null,
            'cultivo' => $afeccion->cultivo ? [
                'id' => $afeccion->cultivo->id,
                'nombre' => $afeccion->cultivo->nombre,
            ] : null,
            'bancal' => $afeccion->bancal ? [
                'id' => $afeccion->bancal->id,
                'nombre' => $afeccion->bancal->nombre,
                'ubicacion' => $afeccion->bancal->ubicacion ?? null,
            ] : null,
        ];
    }
}