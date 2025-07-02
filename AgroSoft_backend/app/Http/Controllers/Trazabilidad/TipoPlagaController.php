<?php

namespace App\Http\Controllers\Trazabilidad;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\Trazabilidad\TipoPlaga;

class TipoPlagaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $tipoPlagas = TipoPlaga::all();
        return response()->json($tipoPlagas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:30|unique:tipo_plagas',
            'descripcion' => 'required|string',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->only(['nombre', 'descripcion']);

        if ($request->hasFile('img')) {
            $path = $request->file('img')->store('tipos_plagas_images', 'public');
            $data['img'] = $path;
        }

        $tipoPlaga = TipoPlaga::create($data);
        return response()->json($tipoPlaga, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(TipoPlaga $tipoPlaga): JsonResponse
    {
        return response()->json($tipoPlaga);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TipoPlaga $tipoPlaga): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:30|unique:tipo_especies,nombre,' . $tipoPlaga->id,
            'descripcion' => 'required|string',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->only(['nombre', 'descripcion']);

        if ($request->hasFile('img')) {
            // Delete old image if exists
            if ($tipoPlaga->img) {
                Storage::disk('public')->delete($tipoPlaga->img);
            }
            $path = $request->file('img')->store('tipos_especie_images', 'public');
            $data['img'] = $path;
        }

        $tipoPlaga->update($data);
        return response()->json($tipoPlaga);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TipoPlaga $tipoPlaga): JsonResponse
    {
        if ($tipoPlaga->img) {
            Storage::disk('public')->delete($tipoPlaga->img);
        }
        $tipoPlaga->delete();
        return response()->json(null, 204);
    }
}