<?php

namespace App\Models\Finanzas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
