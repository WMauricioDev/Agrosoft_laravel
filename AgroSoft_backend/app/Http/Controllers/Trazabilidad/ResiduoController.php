<?php

namespace App\Http\Controllers\Trazabilidad;

use App\Models\Trazabilidad\Residuo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ResiduoController extends Controller
{
    public function index()
    {
        $residuos = Residuo::with(['tipoResiduo', 'cosecha'])->get();
        return response()->json([
            'success' => true,
            'message' => 'Lista de residuos obtenida correctamente',
            'data' => $residuos
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_cosecha' => 'required|exists:cosechas,id',
            'id_tipo_residuo' => 'required|exists:tipo_residuos,id',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha' => 'required|date',
            'cantidad' => 'required|numeric|min:0'
        ]);

        $residuo = Residuo::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Residuo creado correctamente',
            'data' => $residuo
        ], 201);
    }

    public function show($id)
    {
        $residuo = Residuo::with(['tipoResiduo', 'cosecha'])->find($id);

        if (!$residuo) {
            return response()->json(['success' => false, 'message' => 'Residuo no encontrado'], 404);
        }

        return response()->json(['success' => true, 'data' => $residuo]);
    }

    public function update(Request $request, $id)
    {
        $residuo = Residuo::find($id);

        if (!$residuo) {
            return response()->json(['success' => false, 'message' => 'Residuo no encontrado'], 404);
        }

        $validated = $request->validate([
            'id_cosecha' => 'sometimes|required|exists:cosechas,id',
            'id_tipo_residuo' => 'sometimes|required|exists:tipo_residuos,id',
            'nombre' => 'sometimes|required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha' => 'sometimes|required|date',
            'cantidad' => 'sometimes|required|numeric|min:0'
        ]);

        $residuo->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Residuo actualizado correctamente',
            'data' => $residuo
        ]);
    }

    public function destroy($id)
    {
        $residuo = Residuo::find($id);

        if (!$residuo) {
            return response()->json(['success' => false, 'message' => 'Residuo no encontrado'], 404);
        }

        $residuo->delete();

        return response()->json([
            'success' => true,
            'message' => 'Residuo eliminado correctamente'
        ]);
    }
}
