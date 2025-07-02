<?php

namespace App\Http\Controllers\Inventario;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Inventario\Bodega;
use App\Http\Controllers\Controller;

class BodegaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $bodegas = Bodega::all();
        return response()->json($bodegas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'telefono' => 'required|string|max:50',
            'activo' => 'boolean',
            'capacidad' => 'required|integer|min:0',
            'ubicacion' => 'nullable|string|max:255',
        ]);

        $bodega = Bodega::create($validated);
        return response()->json($bodega, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Bodega $bodega): JsonResponse
    {
        return response()->json($bodega);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Bodega $bodega): JsonResponse
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'telefono' => 'required|string|max:50',
            'activo' => 'boolean',
            'capacidad' => 'required|integer|min:0',
            'ubicacion' => 'nullable|string|max:255',
        ]);

        $bodega->update($validated);
        return response()->json($bodega);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bodega $bodega): JsonResponse
    {
        $bodega->delete();
        return response()->json(null, 204);
    }
}