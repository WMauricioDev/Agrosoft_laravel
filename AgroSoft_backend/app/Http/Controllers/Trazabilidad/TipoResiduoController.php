<?php

namespace App\Http\Controllers\Trazabilidad;

use App\Models\Trazabilidad\TipoResiduo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
class TipoResiduoController extends Controller
{
    public function index()
    {
        $tipos = TipoResiduo::all();
        return response()->json([
            'success' => true,
            'message' => 'Lista de tipos de residuo obtenida correctamente',
            'data' => $tipos
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string'
        ]);

        $tipo = TipoResiduo::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Tipo de residuo creado correctamente',
            'data' => $tipo
        ], 201);
    }

    public function show($id)
    {
        $tipo = TipoResiduo::find($id);

        if (!$tipo) {
            return response()->json(['success' => false, 'message' => 'Tipo de residuo no encontrado'], 404);
        }

        return response()->json(['success' => true, 'data' => $tipo]);
    }

    public function update(Request $request, $id)
    {
        $tipo = TipoResiduo::find($id);

        if (!$tipo) {
            return response()->json(['success' => false, 'message' => 'Tipo de residuo no encontrado'], 404);
        }

        $validated = $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'descripcion' => 'nullable|string'
        ]);

        $tipo->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Tipo de residuo actualizado correctamente',
            'data' => $tipo
        ]);
    }

    public function destroy($id)
    {
        $tipo = TipoResiduo::find($id);

        if (!$tipo) {
            return response()->json(['success' => false, 'message' => 'Tipo de residuo no encontrado'], 404);
        }

        $tipo->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tipo de residuo eliminado correctamente'
        ]);
    }
}
