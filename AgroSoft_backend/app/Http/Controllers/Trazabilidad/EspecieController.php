<?php

namespace App\Http\Controllers\Trazabilidad;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\Trazabilidad\Especie;

class EspecieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $especies = Especie::with('tipoEspecie')->get();
        return response()->json($especies);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'tipo_especie_id' => 'required|exists:tipo_especies,id',
            'nombre' => 'required|string|max:30|unique:especies',
            'descripcion' => 'required|string',
            'largo_crecimiento' => 'required|integer|min:0',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->only(['tipo_especie_id', 'nombre', 'descripcion', 'largo_crecimiento']);

        if ($request->hasFile('img')) {
            $path = $request->file('img')->store('especies_images', 'public');
            $data['img'] = $path;
        }

        $especie = Especie::create($data);
        $especie->load('tipoEspecie');
        return response()->json($especie, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Especie $especie): JsonResponse
    {
        $especie->load('tipoEspecie');
        return response()->json($especie);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Especie $especie): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'tipo_especie_id' => 'required|exists:tipo_especies,id',
            'nombre' => 'required|string|max:30|unique:especies,nombre,' . $especie->id,
            'descripcion' => 'required|string',
            'largo_crecimiento' => 'required|integer|min:0',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->only(['tipo_especie_id', 'nombre', 'descripcion', 'largo_crecimiento']);

        if ($request->hasFile('img')) {
            if ($especie->img) {
                Storage::disk('public')->delete($especie->img);
            }
            $path = $request->file('img')->store('especies_images', 'public');
            $data['img'] = $path;
        }

        $especie->update($data);
        $especie->load('tipoEspecie');
        return response()->json($especie);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Especie $especie): JsonResponse
    {
        if ($especie->img) {
            Storage::disk('public')->delete($especie->img);
        }
        $especie->delete();
        return response()->json(null, 204);
    }
}