<?php

namespace App\Http\Controllers\Finanzas;
use App\http\Controllers\Controller;
use App\Http\Requests\Finanzas\StoreVentaRequest;
use Illuminate\Http\Request;
use App\Models\Finanzas\Venta;
use Illuminate\Http\JsonResponse;

class VentaController extends Controller
{
    
  public function store(StoreVentaRequest $request): JsonResponse
{
    $validated = $request->validated();

    // Convertir la fecha al formato que espera MySQL
    $fecha = date('Y-m-d H:i:s', strtotime($validated['fecha']));

    // Crear venta
    $venta = Venta::create([
        'fecha' => $fecha,
        'monto_entregado' => $validated['monto_entregado'],
        'cambio' => $validated['cambio'],
    ]);

    // Crear detalles
    foreach ($validated['detalles'] as $detalle) {
        $venta->detalles()->create([
            'producto' => $detalle['producto'],
            'cantidad' => $detalle['cantidad'],
            'unidad_medidas' => $detalle['unidad_medidas'],
            'total' => $detalle['total'],
            'precio_unitario' => $detalle['precio_unitario'] ?? null,
        ]);
    }

    return response()->json($venta->load('detalles'), 201);
}


public function index()
    {
        $venta = Venta::all();


        $ventas = Venta::with('detalles')->get();
        return response()->json([
            'success'=>true,
            'message'=>'Lista de ventas obtenida correctamente',
            'data'=>$venta
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
