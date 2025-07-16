<?php

namespace App\Http\Controllers\Trazabilidad;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use App\Models\Trazabilidad\TipoActividad;
use App\Http\Requests\Trazabilidad\StoreTipoActividadRequest;

class TipoActividadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $tipos = TipoActividad::all();
        return response()->json([
            'success' => true,
            'message' => 'Tipo de actividad obtenido correctamente.',
            'data' => $tipos,
        ], 201);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTipoActividadRequest $request): JsonResponse
    {
        $tipos = TipoActividad::create($request->validated());
        return response()->json($tipos, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(TipoActividad $tipoActividad): JsonResponse
    {
        return response()->json($tipoActividad);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TipoActividad $tipoActividad): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255|unique:tipo_actividades,nombre,' . $tipoActividad->id,
            'descripcion' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $tipoActividad->update($request->only(['nombre', 'descripcion']));
        return response()->json($tipoActividad);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TipoActividad $tipoActividad): JsonResponse
    {
        $tipoActividad->delete();
        return response()->json(null, 204);
    }
}