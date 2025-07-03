<?php

namespace App\Models\Finanzas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Inventario\PrecioProducto;
use App\Models\Trazabilidad\UnidadMedida;
class DetalleVenta extends Model
{
    use HasFactory;

    protected $fillable = [
        'venta_id',
        'producto',
        'cantidad',
        'unidad_medidas',
        'total',
        'precio_unitario',
    ];

    
    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }

    public function productoPrecio() { return $this->belongsTo(PrecioProducto::class, 'producto'); }
public function unidadMedidas() { return $this->belongsTo(UnidadMedida::class, 'unidad_medidas'); }
}
