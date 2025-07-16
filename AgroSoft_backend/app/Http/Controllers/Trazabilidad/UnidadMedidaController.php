<?php

namespace App\Http\Controllers\Trazabilidad;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use App\Models\Trazabilidad\UnidadMedida;

class UnidadMedidaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $unidades = UnidadMedida::all();
        return response()->json([
            'success' => true,
            'message' => 'Unidades de medidas obtenidas correctamente.',
            'data' => $unidades,
        ], 201);   
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:50|unique:unidad_medidas',
            'descripcion' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $unidad = UnidadMedida::create($request->only(['nombre', 'descripcion']));
        return response()->json($unidad, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(UnidadMedida $unidadMedida): JsonResponse
    {
        return response()->json($unidadMedida);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UnidadMedida $unidadMedida): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:50|unique:unidad_medidas,nombre,' . $unidadMedida->id,
            'descripcion' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $unidadMedida->update($request->only(['nombre', 'descripcion']));
        return response()->json($unidadMedida);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UnidadMedida $unidadMedida): JsonResponse
    {
        $unidadMedida->delete();
        return response()->json(null, 204);
    }
}