<?php

namespace App\Models\Finanzas;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    protected $fillable = [
        'fecha',
        'monto_entregado',
        'cambio',
    ];

   
    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class);
    }
}
