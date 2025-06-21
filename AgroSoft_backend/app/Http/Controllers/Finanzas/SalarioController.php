<?php



namespace App\Http\Controllers\Finanzas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use App\Models\Finanzas\Salario;
use App\Models\Usuarios\Roles;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsUserAuth;

class SalarioController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', IsAdmin::class, IsUserAuth::class])->except(['index', 'show', 'actuales']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $salarios = Salario::with('rol')->get();
        return response()->json([
            'success' => true,
            'message' => 'Lista de salarios obtenida correctamente.',
            'data' => $salarios,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'rol_id' => 'required|exists:roles,id',
            'fecha_de_implementacion' => 'required|date',
            'valor_jornal' => 'required|numeric|between:0,99999999.99',
            'activo' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Validar unicidad de rol_id y fecha_de_implementacion
        if (Salario::where('rol_id', $request->rol_id)
            ->where('fecha_de_implementacion', $request->fecha_de_implementacion)
            ->exists()) {
            return response()->json(['errors' => ['Ya existe un salario para este rol en esta fecha.']], 422);
        }

        // Desactivar salarios anteriores para el mismo rol
        Salario::where('rol_id', $request->rol_id)
            ->where('activo', true)
            ->update(['activo' => false]);

        $salario = Salario::create($request->only([
            'rol_id',
            'fecha_de_implementacion',
            'valor_jornal',
            'activo',
        ]));
        $salario->load('rol');
        return response()->json([
            'success' => true,
            'message' => 'Salario creado correctamente.',
            'data' => $salario,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Salario $salario): JsonResponse
    {
        $salario->load('rol');
        return response()->json([
            'success' => true,
            'message' => 'Salario obtenido correctamente.',
            'data' => $salario,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Salario $salario): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'rol_id' => 'required|exists:roles,id',
            'fecha_de_implementacion' => 'required|date',
            'valor_jornal' => 'required|numeric|between:0,99999999.99',
            'activo' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Validar unicidad de rol_id y fecha_de_implementacion, excluyendo el registro actual
        if (Salario::where('rol_id', $request->rol_id)
            ->where('fecha_de_implementacion', $request->fecha_de_implementacion)
            ->where('id', '!=', $salario->id)
            ->exists()) {
            return response()->json(['errors' => ['Ya existe un salario para este rol en esta fecha.']], 422);
        }

        // Si se activa este salario, desactivar otros para el mismo rol
        if ($request->activo) {
            Salario::where('rol_id', $request->rol_id)
                ->where('activo', true)
                ->where('id', '!=', $salario->id)
                ->update(['activo' => false]);
        }

        $salario->update($request->only([
            'rol_id',
            'fecha_de_implementacion',
            'valor_jornal',
            'activo',
        ]));
        $salario->load('rol');
        return response()->json([
            'success' => true,
            'message' => 'Salario actualizado correctamente.',
            'data' => $salario,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Salario $salario): JsonResponse
    {
        $salario->delete();
        return response()->json([
            'success' => true,
            'message' => 'Salario eliminado correctamente.',
        ], 204);
    }

    /**
     * Get the latest active salary for each role.
     */
    public function actuales(Request $request): JsonResponse
    {
        $salarios = [];
        $roles = Roles::all();

        foreach ($roles as $rol) {
            $salario = Salario::where('rol_id', $rol->id)
                ->where('activo', true)
                ->orderBy('fecha_de_implementacion', 'desc')
                ->first();

            if ($salario) {
                $salarios[] = $salario;
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Lista de salarios activos obtenida correctamente.',
            'data' => $salarios,
        ]);
    }
}