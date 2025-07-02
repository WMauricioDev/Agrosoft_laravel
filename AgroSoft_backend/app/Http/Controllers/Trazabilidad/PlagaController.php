<?php

namespace App\Http\Controllers\Trazabilidad;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\Trazabilidad\Plaga;
use Illuminate\Support\Facades\Log;

class PlagaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $plagas = Plaga::with('tipoPlaga')->get()->map(function ($plaga) {
            return [
                'id' => $plaga->id,
                'fk_tipo_plaga' => $plaga->fk_tipo_plaga,
                'tipo_plaga' => $plaga->tipoPlaga ? $plaga->tipoPlaga->nombre : null,
                'nombre' => $plaga->nombre,
                'descripcion' => $plaga->descripcion,
                'img' => $plaga->img ? asset('storage/' . $plaga->img) : null,
            ];
        });

        return response()->json($plagas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        Log::info('Datos recibidos en store:', $request->all());

        $validator = Validator::make($request->all(), [
            'fk_tipo_plaga' => 'required|exists:tipo_plagas,id',
            'nombre' => 'required|string|max:50|unique:plagas',
            'descripcion' => 'required|string',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            Log::error('Errores de validación en store:', $validator->errors()->toArray());
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->only(['fk_tipo_plaga', 'nombre', 'descripcion']);

        if ($request->hasFile('img')) {
            $path = $request->file('img')->store('plagas_images', 'public');
            $data['img'] = $path;
        }

        $plaga = Plaga::create($data);
        $plaga->load('tipoPlaga');
        return response()->json([
            'id' => $plaga->id,
            'fk_tipo_plaga' => $plaga->fk_tipo_plaga,
            'tipo_plaga' => $plaga->tipoPlaga ? $plaga->tipoPlaga->nombre : null,
            'nombre' => $plaga->nombre,
            'descripcion' => $plaga->descripcion,
            'img' => $plaga->img ? asset('storage/' . $plaga->img) : null,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Plaga $plaga): JsonResponse
    {
        $plaga->load('tipoPlaga');
        return response()->json([
            'id' => $plaga->id,
            'fk_tipo_plaga' => $plaga->fk_tipo_plaga,
            'tipo_plaga' => $plaga->tipoPlaga ? $plaga->tipoPlaga->nombre : null,
            'nombre' => $plaga->nombre,
            'descripcion' => $plaga->descripcion,
            'img' => $plaga->img ? asset('storage/' . $plaga->img) : null,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Plaga $plaga): JsonResponse
    {
        Log::info('Datos recibidos en update:', $request->all());

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:50|unique:plagas,nombre,' . $plaga->id,
            'descripcion' => 'required|string',
            'fk_tipo_plaga' => 'sometimes|exists:tipo_plagas,id',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            Log::error('Errores de validación en update:', $validator->errors()->toArray());
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->only(['nombre', 'descripcion']);

        // Solo actualizar fk_tipo_plaga si se envía
        if ($request->has('fk_tipo_plaga')) {
            $data['fk_tipo_plaga'] = $request->input('fk_tipo_plaga');
        }

        // Manejar la imagen si se envía
        if ($request->hasFile('img')) {
            if ($plaga->img && Storage::disk('public')->exists($plaga->img)) {
                Log::info('Eliminando imagen antigua: ' . $plaga->img);
                Storage::disk('public')->delete($plaga->img);
            }
            $path = $request->file('img')->store('plagas_images', 'public');
            $data['img'] = $path;
        }

        try {
            $plaga->update($data);
            $plaga->load('tipoPlaga');
            return response()->json([
                'id' => $plaga->id,
                'fk_tipo_plaga' => $plaga->fk_tipo_plaga,
                'tipo_plaga' => $plaga->tipoPlaga ? $plaga->tipoPlaga->nombre : null,
                'nombre' => $plaga->nombre,
                'descripcion' => $plaga->descripcion,
                'img' => $plaga->img ? asset('storage/' . $plaga->img) : null,
            ]);
        } catch (\Exception $e) {
            Log::error('Error al actualizar la plaga: ' . $e->getMessage());
            return response()->json(['error' => 'Error al actualizar la plaga'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Plaga $plaga): JsonResponse
    {
        if ($plaga->img && Storage::disk('public')->exists($plaga->img)) {
            Log::info('Eliminando imagen: ' . $plaga->img);
            Storage::disk('public')->delete($plaga->img);
        }
        $plaga->delete();
        return response()->json(null, 204);
    }
}