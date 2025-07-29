<?php

namespace App\Http\Controllers\Reportes\Usuarios;

use Illuminate\Http\Request;
use App\Models\Usuarios\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

class ReporteUsuariosController extends Controller
{
    public function generar(Request $request)
    {
        $fecha_inicio = $request->query('fecha_inicio');
        $fecha_fin = $request->query('fecha_fin');

        if (!$fecha_inicio || !$fecha_fin) {
            return response()->json([
                'success' => false,
                'message' => "Debes proporcionar 'fecha_inicio' y 'fecha_fin'"
            ], 400);
        }

        $usuarios = User::with('rol')
            ->whereBetween('created_at', [
                Carbon::parse($fecha_inicio)->startOfDay(),
                Carbon::parse($fecha_fin)->endOfDay()
            ])
            ->get();

        $pdf = Pdf::loadView('reportes.Usuarios.usuarios', [
            'usuarios' => $usuarios,
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin' => $fecha_fin
        ]);

        return $pdf->download('reporte_usuarios.pdf');
    }
}
