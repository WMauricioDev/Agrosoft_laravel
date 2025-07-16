<?php

namespace App\Http\Controllers\Trazabilidad;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\Trazabilidad\Bancal;
use App\Http\Requests\Trazabilidad\StoreBancalRequest;
use App\Http\Requests\Trazabilidad\UpdateBancalRequest;

class BancalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $bancals = Bancal::with('lote')->get();
        return response()->json([
            'success' => true,
            'message' => 'Bancal obtenida correctamente.',
            'data' => $bancals,
        ], 201);    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBancalRequest $request): JsonResponse
    {
        $bancal = Bancal::create($request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Bancal creado correctamente.',
            'data' => $bancal,
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
    public function update(UpdateBancalRequest $request, Bancal $bancal): JsonResponse
    {
        $bancal->update($request->only([
            'nombre',
            'tam_x',
            'tam_y',
            'latitud',
            'longitud',
            'lote_id',
        ]));
        $bancal->load('lote');
        return response()->json([
            'success' => true,
            'message' => 'Bancal creado correctamente.',
            'data' => $bancal,
        ], 201);    
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bancal $bancal): JsonResponse
    {
        $bancal->delete();
        return response()->json([
            'success' => true,
            'message' => 'Bancal eliminada correctamente.',
            'data' => $bancal,
        ], 201);    }
}