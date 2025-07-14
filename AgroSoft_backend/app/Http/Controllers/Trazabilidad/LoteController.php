<?php

namespace App\Http\Controllers\Trazabilidad;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\Trazabilidad\Lote;
use App\Http\Requests\Trazabilidad\StoreLoteRequest;
use App\Http\Requests\Trazabilidad\UpdateLoteRequest;

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
    public function store(StoreLoteRequest $request): JsonResponse
    {
        $lote = Lote::create($request->validated());
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
    public function update(UpdateLoteRequest $request, Lote $lote): JsonResponse
    {
       

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