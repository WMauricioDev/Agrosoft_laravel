<?php

namespace App\Http\Controllers\Finanzas;
use App\http\Controllers\Controller;
use App\Http\Requests\Finanzas\StoreVentaRequest;
use Illuminate\Http\Request;
use App\Models\Finanzas\Venta;
use App\Models\Inventario\PrecioProducto;
use Illuminate\Http\JsonResponse;
use Barryvdh\DomPDF\Facade\Pdf; 


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
    // Busca el producto en la tabla de precios
    $precioProducto = PrecioProducto::find($detalle['producto']);

    if (!$precioProducto) {
        return response()->json([
            'success' => false,
            'message' => "Producto con ID {$detalle['producto']} no encontrado en precios."
        ], 400);
    }

    // Calcula el precio unitario real
    $precioUnitario = $precioProducto->precio;
    $total = $precioUnitario * $detalle['cantidad'];

    // Crea el detalle
    $venta->detalles()->create([
        'producto' => $detalle['producto'],
        'cantidad' => $detalle['cantidad'],
        'unidad_medidas' => $detalle['unidad_medidas'],
        'total' => $total,
        'precio_unitario' => $precioUnitario,
    ]);

    // Descuenta stock
    $nuevoStock = $precioProducto->stock - $detalle['cantidad'];
    if ($nuevoStock < 0) {
        return response()->json([
            'success' => false,
            'message' => "Stock insuficiente para el producto ID {$detalle['producto']}"
        ], 400);
    }
    $precioProducto->stock = $nuevoStock;
    $precioProducto->save();
}
    

    return response()->json($venta->load('detalles'), 201);
}


public function index()
    {
        $venta = Venta::all();


        $venta = Venta::with('detalles')->get();
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

 public function facturaPDF($id)
{
    $venta = Venta::with('detalles')->find($id);

    if (!$venta) {
        return response()->json(['error' => 'Venta no encontrada'], 404);
    }

    $pdf = Pdf::loadView('ventas.factura', compact('venta'));

    return $pdf->stream("factura_{$venta->id}.pdf");
}

}
