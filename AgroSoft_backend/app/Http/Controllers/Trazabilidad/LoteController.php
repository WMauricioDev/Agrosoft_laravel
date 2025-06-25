<?php

namespace App\Http\Controllers\Trazabilidad;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use App\Models\Trazabilidad\Lote;

class LoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $lotes = Lote::all();
        return response()->json($lotes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:15|unique:lotes',
            'descripcion' => 'nullable|string',
            'activo' => 'required|boolean',
            'tam_x' => 'required|numeric|between:0,999.99',
            'tam_y' => 'required|numeric|between:0,999.99',
            'latitud' => 'required|numeric|between:-999.999999,999.999999',
            'longitud' => 'required|numeric|between:-999.999999,999.999999',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $lote = Lote::create($request->only([
            'nombre',
            'descripcion',
            'activo',
            'tam_x',
            'tam_y',
            'latitud',
            'longitud',
        ]));
        return response()->json($lote, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Lote $lote): JsonResponse
    {
        return response()->json($lote);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lote $lote): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:15|unique:lotes,nombre,' . $lote->id,
            'descripcion' => 'nullable|string',
            'activo' => 'required|boolean',
            'tam_x' => 'required|numeric|between:0,999.99',
            'tam_y' => 'required|numeric|between:0,999.99',
            'latitud' => 'required|numeric|between:-999.999999,999.999999',
            'longitud' => 'required|numeric|between:-999.999999,999.999999',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $lote->update($request->only([
            'nombre',
            'descripcion',
            'activo',
            'tam_x',
            'tam_y',
            'latitud',
            'longitud',
        ]));
        return response()->json($lote);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lote $lote): JsonResponse
    {
        $lote->delete();
        return response()->json(null, 204);
    }
}