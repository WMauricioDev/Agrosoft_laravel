<?php

namespace App\Models\Trazabilidad;

use Illuminate\Database\Eloquent\Model;

class UnidadMedida extends Model
{
    protected $table = 'unidad_medidas';
    
    protected $fillable = [
        'nombre',
        'descripcion',
    ];
}
