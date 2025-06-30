<?php

namespace App\Http\Controllers\Inventario;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use App\Models\Inventario\Herramienta;

class HerramientaController extends Controller
{

    /**
     * Muestra una lista de todos los recursos.
     */
    public function index(): JsonResponse
    {
        $herramientas = Herramienta::all()->map(function ($herramienta) {
            return [
                'id' => $herramienta->id,
                'nombre' => $herramienta->nombre,
                'descripcion' => $herramienta->descripcion,
                'cantidad' => $herramienta->cantidad,
                'estado' => $herramienta->estado,
                'activo' => $herramienta->activo,
                'fecha_registro' => $herramienta->fecha_registro->toISOString(),
                'precio' => $herramienta->precio,
            ];
        });
        return response()->json($herramientas);
    }

    /**
     * Almacena un nuevo recurso en la base de datos.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'cantidad' => 'required|integer|min:0',
            'estado' => 'required|string|max:50',
            'activo' => 'nullable|boolean',
            'precio' => 'required|numeric|min:0',
            'fecha_registro' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $herramienta = Herramienta::create(array_merge(
            $request->only([
                'nombre',
                'descripcion',
                'cantidad',
                'estado',
                'activo',
                'precio',
            ]),
            ['fecha_registro' => $request->fecha_registro ?? now()]
        ));

        return response()->json([
            'mensaje' => 'Herramienta registrada con éxito',
            'herramienta' => [
                'id' => $herramienta->id,
                'nombre' => $herramienta->nombre,
                'descripcion' => $herramienta->descripcion,
                'cantidad' => $herramienta->cantidad,
                'estado' => $herramienta->estado,
                'activo' => $herramienta->activo,
                'fecha_registro' => $herramienta->fecha_registro->toISOString(),
                'precio' => $herramienta->precio,
            ],
        ], 201);
    }

    /**
     * Muestra un recurso específico.
     */
    public function show(Herramienta $herramienta): JsonResponse
    {
        return response()->json([
            'id' => $herramienta->id,
            'nombre' => $herramienta->nombre,
            'descripcion' => $herramienta->descripcion,
            'cantidad' => $herramienta->cantidad,
            'estado' => $herramienta->estado,
            'activo' => $herramienta->activo,
            'fecha_registro' => $herramienta->fecha_registro->toISOString(),
            'precio' => $herramienta->precio,
        ]);
    }

    /**
     * Actualiza un recurso específico en la base de datos.
     */
    public function update(Request $request, Herramienta $herramienta): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'cantidad' => 'required|integer|min:0',
            'estado' => 'required|string|max:50',
            'activo' => 'nullable|boolean',
            'precio' => 'required|numeric|min:0',
            'fecha_registro' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $herramienta->update($request->only([
            'nombre',
            'descripcion',
            'cantidad',
            'estado',
            'activo',
            'precio',
            'fecha_registro',
        ]));

        return response()->json([
            'id' => $herramienta->id,
            'nombre' => $herramienta->nombre,
            'descripcion' => $herramienta->descripcion,
            'cantidad' => $herramienta->cantidad,
            'estado' => $herramienta->estado,
            'activo' => $herramienta->activo,
            'fecha_registro' => $herramienta->fecha_registro->toISOString(),
            'precio' => $herramienta->precio,
        ]);
    }

    /**
     * Elimina un recurso específico de la base de datos.
     */
    public function destroy(Herramienta $herramienta): JsonResponse
    {
        $herramienta->delete();
        return response()->json(null, 204);
    }
}