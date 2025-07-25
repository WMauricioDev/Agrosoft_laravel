<?php

namespace App\Http\Controllers\Finanzas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use App\Models\Finanzas\Pago;
use App\Models\Finanzas\Salario;
use App\Models\Usuarios\User;
use App\Models\Trazabilidad\Actividades;
use Carbon\Carbon;

class PagoController extends Controller
{
    /**
     * Transform payment data to include usuario_nombre and usuario_rol.
     */
private function transformPago($pago)
{
    return [
        'id' => $pago->id,
        'usuario_id' => $pago->usuario_id,
        'usuario_nombre' => $pago->usuario ? ($pago->usuario->apellido ? $pago->usuario->nombre . ' ' . $pago->usuario->apellido : $pago->usuario->nombre) : null,
        'usuario_rol' => $pago->usuario && $pago->usuario->rol ? $pago->usuario->rol->nombre : null,
        'salario_id' => $pago->salario_id,
        'fecha_inicio' => $pago->fecha_inicio ? $pago->fecha_inicio->format('Y-m-d') : null,
        'fecha_fin' => $pago->fecha_fin ? $pago->fecha_fin->format('Y-m-d') : null,
        'horas_trabajadas' => $pago->horas_trabajadas,
        'jornales' => $pago->jornales,
        'total_pago' => $pago->total_pago,
        'fecha_calculo' => $pago->fecha_calculo ? $pago->fecha_calculo->format('Y-m-d H:i:s') : null,
        'actividades' => $pago->actividades->pluck('id')->toArray(),
    ];
}

    /**
     * Display a listing of the resource.
     */
public function index(): JsonResponse
{
    $pagos = Pago::with(['usuario.rol', 'salario', 'actividades'])->get();

    $transformed = $pagos->map(function ($pago) {
        return $this->transformPago($pago);
    });

    return response()->json([
        'mensaje' => 'Lista de pagos obtenida con éxito',
        'pagos' => $transformed,
    ]);
}

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request): JsonResponse
{
    $validator = Validator::make($request->all(), [
        'usuario_id' => 'required|exists:users,id',
        'salario_id' => 'required|exists:salarios,id',
        'fecha_inicio' => 'required|date',
        'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        'horas_trabajadas' => 'nullable|numeric|min:0',
        'jornales' => 'nullable|numeric|min:0',
        'total_pago' => 'nullable|numeric|min:0',
        'actividades' => 'nullable|array',
        'actividades.*' => 'exists:actividades,id',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()->all()], 422);
    }

    $pago = Pago::create([
        'usuario_id' => $request->usuario_id,
        'salario_id' => $request->salario_id,
        'fecha_inicio' => $request->fecha_inicio,
        'fecha_fin' => $request->fecha_fin,
        'horas_trabajadas' => $request->horas_trabajadas,
        'jornales' => $request->jornales,
        'total_pago' => $request->total_pago,
        'fecha_calculo' => Carbon::now(),
    ]);

    if ($request->has('actividades')) {
        $pago->actividades()->sync($request->actividades);
    }

    $pago->load(['usuario', 'salario', 'actividades']);
    return response()->json([
        'mensaje' => 'Pago registrado con éxito',
        'pago' => $this->transformPago($pago),
    ], 201);
}
    /**
     * Display the specified resource.
     */
    public function show(Pago $pago): JsonResponse
    {
        $pago->load(['usuario', 'salario', 'actividades']);
        return response()->json($this->transformPago($pago));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pago $pago): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'usuario_id' => 'required|exists:users,id',
            'salario_id' => 'required|exists:salarios,id',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'horas_trabajadas' => 'nullable|numeric|min:0',
            'jornales' => 'nullable|numeric|min:0',
            'total_pago' => 'nullable|numeric|min:0',
            'actividades' => 'nullable|array',
            'actividades.*' => 'exists:actividades,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 422);
        }

        $pago->update($request->only([
            'usuario_id',
            'salario_id',
            'fecha_inicio',
            'fecha_fin',
            'horas_trabajadas',
            'jornales',
            'total_pago',
        ]));

        if ($request->has('actividades')) {
            $pago->actividades()->sync($request->actividades);
        }

        $pago->load(['usuario', 'salario', 'actividades']);
        return response()->json($this->transformPago($pago));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pago $pago): JsonResponse
    {
        $pago->delete();
        return response()->json(null, 204);
    }

    /**
     * Calculate payment based on user activities.
     */
    public function calcular(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'usuario_id' => 'required|exists:users,id',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 422);
        }

        $usuario = User::find($request->usuario_id);
        if (!$usuario) {
            return response()->json(['errors' => ['Usuario no encontrado']], 422);
        }

        if (!$usuario->rol) {
            return response()->json(['errors' => ['El usuario no tiene un rol asignado']], 422);
        }

        $fecha_inicio = Carbon::parse($request->fecha_inicio);
        $fecha_fin = Carbon::parse($request->fecha_fin);

        $actividades = Actividades::whereHas('usuarios', function ($query) use ($usuario) {
            $query->where('users.id', $usuario->id);
        })
            ->where('estado', 'COMPLETADA')
            ->whereDate('fecha_fin', '>=', $fecha_inicio)
            ->whereDate('fecha_fin', '<=', $fecha_fin)
            ->get();

        if ($actividades->isEmpty()) {
            return response()->json(['errors' => ['No hay actividades completadas en el rango especificado']], 422);
        }

        $salario = Salario::where('rol_id', $usuario->rol_id)
            ->where('activo', true)
            ->where('fecha_de_implementacion', '<=', $fecha_fin)
            ->orderBy('fecha_de_implementacion', 'desc')
            ->first();

        if (!$salario) {
            return response()->json(['errors' => ["No existe un salario configurado para el rol {$usuario->rol->nombre}"]], 422);
        }

        $total_segundos = $actividades->sum(function ($actividad) {
            return Carbon::parse($actividad->fecha_fin)->diffInSeconds(Carbon::parse($actividad->fecha_inicio));
        });
        $horas_trabajadas = $total_segundos / 3600;
        $jornales = $horas_trabajadas / 8; 
        $total_pago = $jornales * $salario->valor_jornal;

       $pago = Pago::create([
    'usuario_id' => $usuario->id,
    'salario_id' => $salario->id,
    'fecha_inicio' => $fecha_inicio,
    'fecha_fin' => $fecha_fin,
    'horas_trabajadas' => round($horas_trabajadas, 2),
    'jornales' => round($jornales, 2),
    'total_pago' => round($total_pago, 2),
    'fecha_calculo' => Carbon::now(),
]);
        $pago->actividades()->sync($actividades->pluck('id')->toArray());
        $pago->load(['usuario', 'salario', 'actividades']);

        return response()->json([
            'mensaje' => 'Pago calculado y registrado con éxito',
            'pago' => $this->transformPago($pago),
        ], 201);
    }
}