<?php

namespace App\Http\Controllers\Trazabilidad;

use App\Models\Trazabilidad\Tipo_Control;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Trazabilidad\TipoControlRequest;
use App\Http\Requests\Trazabilidad\UpdateTipoControlRequest;

class TipoControlController extends Controller
{
    // GET /api/tipo_controles
    public function index(): JsonResponse
    {
        $tipo_controles = Tipo_Control::all();
        return response()->json($tipo_controles);
    }

    // POST /api/tipo_controles
    public function store(TipoControlRequest $request): JsonResponse
    {
        $validated = $request->validate([
            'nombre' => 'required|string|unique:tipo_controles,nombre',
            'descripcion' => 'required|string',
        ]);

        $control = Tipo_Control::create($validated);
        return response()->json($control, 201);
    }

    // GET /api/tipo_controles/{id}
    public function show($id): JsonResponse
    {
        $control = Tipo_Control::find($id);

        if (! $control) {
            return response()->json(['message' => 'Tipo Control no encontrado'], 404);
        }

        return response()->json($control);
    }

    // PUT /api/tipo_controles/{id}
    public function update(UpdateTipoControlRequest $request, $id): JsonResponse
    {
        $control = Tipo_Control::find($id);

        if (! $control) {
            return response()->json(['message' => 'Tipo Control no encontrado'], 404);
        }

        $validated = $request->validate([
            'nombre' => 'sometimes|string|unique:tipo_controles,nombre,' . $id,
            'descripcion' => 'sometimes|string',
        ]);

        $control->update($validated);

        return response()->json($control);
    }

    // DELETE /api/tipo_controles/{id}
    public function destroy($id): JsonResponse
    {
        $control = Tipo_Control::find($id);

        if (! $control) {
            return response()->json(['message' => 'Tipo Control no encontrado'], 404);
        }

        $control->delete();

        return response()->json(['message' => 'Control eliminado con éxito']);
    }
}
