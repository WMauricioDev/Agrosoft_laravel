<?php

namespace App\Http\Controllers\Trazabilidad;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Trazabilidad\Cosecha;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Carbon\Carbon;
use App\Http\Requests\Trazabilidad\StoreCosechaRequest;
use App\Http\Requests\Trazabilidad\UpdateCosechaRequest;

class CosechaController extends Controller
{
  
    public function reportePdf(Request $request): Response
    {
        $fechaInicio = $request->query('fecha_inicio');
        $fechaFin = $request->query('fecha_fin');

        if (!$fechaInicio || !$fechaFin) {
            return response('Error: Debes proporcionar "fecha_inicio" y "fecha_fin"', 400);
        }

        try {
            $fechaInicio = Carbon::parse($fechaInicio);
            $fechaFin = Carbon::parse($fechaFin);
        } catch (\Exception $e) {
            return response('Error: Formato de fecha inválido', 400);
        }

        $cosechas = Cosecha::with('cultivo')
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->get();

        $totalCosechas = $cosechas->count();
        $cantidadTotal = $cosechas->sum('cantidad');
        $promedioCosecha = $totalCosechas > 0 ? $cantidadTotal / $totalCosechas : 0;

        $html = '
            <h1>Reporte de Cosechas</h1>
            <p> Terrestrial: Fecha desde: ' . $fechaInicio->format('Y-m-d') . '</p>
            <p>Fecha hasta: ' . $fechaFin->format('Y-m-d') . '</p>
            <p>Total de cosechas: ' . $totalCosechas . '</p>
            <p>Cantidad total: ' . $cantidadTotal . '</p>
            <p>Promedio por cosecha: ' . number_format($promedioCosecha, 2) . '</p>
            <hr>
            <table border="1" cellpadding="5" cellspacing="0">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Cultivo</th>
                        <th>Cantidad</th>
                    </tr>
                </thead>
                <tbody>';

        foreach ($cosechas as $cosecha) {
            $html .= '
                <tr>
                    <td>' . $cosecha->fecha . '</td>
                    <td>' . $cosecha->cultivo->nombre . '</td>
                    <td>' . $cosecha->cantidad . '</td>
                </tr>';
        }

        $html .= '
                </tbody>
            </table>
        ';

        $pdf = Pdf::loadHTML($html);
        return $pdf->download('reporte_cosechas.pdf');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $cosechas = Cosecha::with(['cultivo', 'unidadMedida'])->get();
        return response()->json($cosechas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCosechaRequest $request): JsonResponse
    {
        $data = $request->validated();
        $cosecha = Cosecha::create($data);
        $cosecha->load(['cultivo', 'unidadMedida']);
        return response()->json($cosecha, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Cosecha $cosecha): JsonResponse
    {
        $cosecha->load(['cultivo', 'unidadMedida']);
        return response()->json($cosecha);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCosechaRequest $request, Cosecha $cosecha): JsonResponse
    {
        $data = $request->validated();
        $cosecha->update($data);
        $cosecha->load(['cultivo', 'unidadMedida']);
        return response()->json($cosecha);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cosecha $cosecha): JsonResponse
    {
        $cosecha->delete();
        return response()->json(null, 204);
    }
}