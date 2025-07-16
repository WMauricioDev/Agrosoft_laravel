<?php

namespace App\Http\Controllers\Trazabilidad;

use App\Http\Controllers\Controller;
use App\Models\Trazabilidad\Controles;
use Illuminate\Http\Request;
use App\Http\Requests\Trazabilidad\ControlRequest;

class ControlesController extends Controller
{
    public function index()
    {
        $controles = Controles::with(['afeccion', 'tipo_control', 'producto', 'responsable'])->get();
        return response()->json($controles);
    }

    public function store(ControlRequest $request)
    {
        $validated = $request->validate([
            'afeccion_id' => 'required|exists:afecciones,id',
            'tipo_control_id' => 'required|exists:tipo_controles,id',
            'producto_id' => 'required|exists:insumos,id',
            'descripcion' => 'required|string|max:255',
            'fecha_control' => 'required|date',
            'responsable_id' => 'required|exists:users,id',
            'efectividad' => 'required|numeric|min:0|max:100',
            'observaciones' => 'nullable|string'
        ]);

        $control = Controles::create($validated);

        return response()->json($control->load(['afeccion', 'tipo_control', 'producto', 'responsable']), 201);
    }

    public function show($id)
    {
        $control = Controles::with(['afeccion', 'tipo_control', 'producto', 'responsable'])->find($id);

        if (!$control) {
            return response()->json(['error' => 'Control no encontrado'], 404);
        }

        return response()->json($control);
    }

    public function update(ControlRequest $request, $id)
    {
        $control = Controles::find($id);

        if (!$control) {
            return response()->json(['error' => 'Control no encontrado'], 404);
        }

        $validated = $request->validate([
            'afeccion_id' => 'sometimes|exists:afecciones,id',
            'tipo_control_id' => 'sometimes|exists:tipo_controles,id',
            'producto_id' => 'sometimes|exists:insumos,id',
            'descripcion' => 'sometimes|string|max:255',
            'fecha_control' => 'sometimes|date',
            'responsable_id' => 'sometimes|exists:users,id',
            'efectividad' => 'sometimes|numeric|min:0|max:100',
            'observaciones' => 'nullable|string'
        ]);

        $control->update($validated);

        return response()->json($control->load(['afeccion', 'tipo_control', 'producto', 'responsable']));
    }

    public function destroy($id)
    {
        $control = Controles::find($id);

        if (!$control) {
            return response()->json(['error' => 'Control no encontrado'], 404);
        }

        $control->delete();

        return response()->json(['message' => 'Control eliminado correctamente']);
    }
}
