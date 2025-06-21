<?php

namespace App\Http\Controllers\Trazabilidad;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use App\Models\Trazabilidad\Bancal;

class BancalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $bancals = Bancal::with('lote')->get();
        return response()->json($bancals);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:15|unique:bancals',
            'tam_x' => 'nullable|numeric|between:0,999.99',
            'tam_y' => 'nullable|numeric|between:0,999.99',
            'latitud' => 'nullable|numeric|between:-999.999999,999.999999',
            'longitud' => 'nullable|numeric|between:-999.999999,999.999999',
            'lote_id' => 'required|exists:lotes,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $bancal = Bancal::create($request->only([
            'nombre',
            'tam_x',
            'tam_y',
            'latitud',
            'longitud',
            'lote_id',
        ]));
       return response()->json([
        'mensaje' => 'Bancal registrado con éxito',
        'bancal' => $bancal,
        ], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(Bancal $bancal): JsonResponse
    {
        $bancal->load('lote');
        return response()->json($bancal);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Bancal $bancal): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:15|unique:bancals,nombre,' . $bancal->id,
            'tam_x' => 'nullable|numeric|between:0,999.99',
            'tam_y' => 'nullable|numeric|between:0,999.99',
            'latitud' => 'nullable|numeric|between:-999.999999,999.999999',
            'longitud' => 'nullable|numeric|between:-999.999999,999.999999',
            'lote_id' => 'required|exists:lotes,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $bancal->update($request->only([
            'nombre',
            'tam_x',
            'tam_y',
            'latitud',
            'longitud',
            'lote_id',
        ]));
        $bancal->load('lote');
        return response()->json($bancal);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bancal $bancal): JsonResponse
    {
        $bancal->delete();
        return response()->json(null, 204);
    }
}