<?php

namespace App\Models\Trazabilidad;

use Illuminate\Database\Eloquent\Model;

class Lote extends Model
{
    protected $table = 'lotes';
    
    protected $fillable = [
        'nombre',
        'descripcion',
        'activo',
        'tam_x',
        'tam_y',
        'latitud',
        'longitud',
    ];
}
