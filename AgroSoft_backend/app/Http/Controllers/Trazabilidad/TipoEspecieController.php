<?php

namespace App\Http\Controllers\Trazabilidad;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\Trazabilidad\TipoEspecie;
use App\Http\Requests\Trazabilidad\UpdateTipoEspecieRequest;
use App\Http\Requests\Trazabilidad\StoreTipoEspecieRequest
;

class TipoEspecieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $tipoEspecies = TipoEspecie::all();
        return response()->json($tipoEspecies);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTipoEspecieRequest $request): JsonResponse
    {
        $data = $request->validated();
        $tipoEspecie = TipoEspecie::create($data);
        return response()->json($tipoEspecie, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(TipoEspecie $tipoEspecie): JsonResponse
    {
        return response()->json($tipoEspecie);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTipoEspecieRequest $request, TipoEspecie $tipoEspecie): JsonResponse
    {
        $data = $request->validated();
        $tipoEspecie->update($data);
        return response()->json($tipoEspecie);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TipoEspecie $tipoEspecie): JsonResponse
    {
        $tipoEspecie->delete();
        return response()->json(null, 204);
    }
}