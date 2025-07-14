<?php

namespace App\Http\Controllers\Inventario;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventario\TipoInsumo;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class TipoInsumoController extends Controller
{
    public function index(): JsonResponse
    {
        $tiposInsumo = TipoInsumo::all();
        return response()->json($tiposInsumo);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:50|unique:tipo_insumos,nombre',
            'descripcion' => 'nullable|string',
            'creada_por_usuario' => 'boolean',
        ]);

        $tipoInsumo = TipoInsumo::create($validated);
        return response()->json($tipoInsumo, 201);
    }

    public function show(TipoInsumo $tipoInsumo): JsonResponse
    {
        return response()->json($tipoInsumo);
    }

    public function update(Request $request, TipoInsumo $tipoInsumo): JsonResponse
    {
        $validated = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:50',
                Rule::unique('tipo_insumos', 'nombre')->ignore($tipoInsumo->id),
            ],
            'descripcion' => 'nullable|string',
            'creada_por_usuario' => 'boolean',
        ]);

        $tipoInsumo->update($validated);
        return response()->json($tipoInsumo);
    }

    public function destroy(TipoInsumo $tipoInsumo): JsonResponse
    {
        $tipoInsumo->delete();
        return response()->json(null, 204);
    }
}
