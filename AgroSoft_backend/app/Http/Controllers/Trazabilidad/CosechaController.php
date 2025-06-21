<?php

namespace App\Http\Controllers\Trazabilidad;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use App\Models\Trazabilidad\Cosecha;

class CosechaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $cosechas = Cosecha::with(['cultivo', 'unidadMedida'])->get();
        return response()->json($cosechas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'cultivo_id' => 'required|exists:cultivos,id',
            'cantidad' => 'required|integer|min:0',
            'unidad_medida_id' => 'required|exists:unidad_medidas,id',
            'fecha' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $cosecha = Cosecha::create($request->only([
            'cultivo_id',
            'cantidad',
            'unidad_medida_id',
            'fecha',
        ]));
        $cosecha->load(['cultivo', 'unidadMedida']);
        return response()->json($cosecha, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Cosecha $cosecha): JsonResponse
    {
        $cosecha->load(['cultivo', 'unidadMedida']);
        return response()->json($cosecha);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cosecha $cosecha): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'cultivo_id' => 'required|exists:cultivos,id',
            'cantidad' => 'required|integer|min:0',
            'unidad_medida_id' => 'required|exists:unidad_medidas,id',
            'fecha' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $cosecha->update($request->only([
            'cultivo_id',
            'cantidad',
            'unidad_medida_id',
            'fecha',
        ]));
        $cosecha->load(['cultivo', 'unidadMedida']);
        return response()->json($cosecha);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cosecha $cosecha): JsonResponse
    {
        $cosecha->delete();
        return response()->json(null, 204);
    }
}