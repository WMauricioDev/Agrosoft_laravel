<?php

namespace App\Http\Controllers\Trazabilidad;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use App\Models\Trazabilidad\Cultivo;

class CultivoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $cultivos = Cultivo::with(['especie', 'bancal', 'unidadMedida'])->get();
        return response()->json($cultivos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'especie_id' => 'required|exists:especies,id',
            'bancal_id' => 'required|exists:bancals,id',
            'nombre' => 'required|string|max:50|unique:cultivos',
            'unidad_medida_id' => 'required|exists:unidad_medidas,id',
            'activo' => 'required|boolean',
            'fecha_siembra' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $cultivo = Cultivo::create($request->only([
            'especie_id',
            'bancal_id',
            'nombre',
            'unidad_medida_id',
            'activo',
            'fecha_siembra',
        ]));
        $cultivo->load(['especie', 'bancal', 'unidadMedida']);
        return response()->json($cultivo, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Cultivo $cultivo): JsonResponse
    {
        $cultivo->load(['especie', 'bancal', 'unidadMedida']);
        return response()->json($cultivo);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cultivo $cultivo): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'especie_id' => 'required|exists:especies,id',
            'bancal_id' => 'required|exists:bancals,id',
            'nombre' => 'required|string|max:50|unique:cultivos,nombre,' . $cultivo->id,
            'unidad_medida_id' => 'required|exists:unidad_medidas,id',
            'activo' => 'required|boolean',
            'fecha_siembra' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $cultivo->update($request->only([
            'especie_id',
            'bancal_id',
            'nombre',
            'unidad_medida_id',
            'activo',
            'fecha_siembra',
        ]));
        $cultivo->load(['especie', 'bancal', 'unidadMedida']);
        return response()->json($cultivo);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cultivo $cultivo): JsonResponse
    {
        $cultivo->delete();
        return response()->json(null, 204);
    }
}