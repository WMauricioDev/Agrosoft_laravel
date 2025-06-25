<?php

namespace App\Http\Controllers\Trazabilidad;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\Trazabilidad\TipoEspecie;

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
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:30|unique:tipo_especies',
            'descripcion' => 'required|string',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->only(['nombre', 'descripcion']);

        if ($request->hasFile('img')) {
            $path = $request->file('img')->store('tipos_especie_images', 'public');
            $data['img'] = $path;
        }

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
    public function update(Request $request, TipoEspecie $tipoEspecie): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:30|unique:tipo_especies,nombre,' . $tipoEspecie->id,
            'descripcion' => 'required|string',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->only(['nombre', 'descripcion']);

        if ($request->hasFile('img')) {
            // Delete old image if exists
            if ($tipoEspecie->img) {
                Storage::disk('public')->delete($tipoEspecie->img);
            }
            $path = $request->file('img')->store('tipos_especie_images', 'public');
            $data['img'] = $path;
        }

        $tipoEspecie->update($data);
        return response()->json($tipoEspecie);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TipoEspecie $tipoEspecie): JsonResponse
    {
        if ($tipoEspecie->img) {
            Storage::disk('public')->delete($tipoEspecie->img);
        }
        $tipoEspecie->delete();
        return response()->json(null, 204);
    }
}