<?php

namespace App\Models\Trazabilidad;

use Illuminate\Database\Eloquent\Model;

class TipoActividad extends Model
{
    //
    protected $table = 'tipo_actividades';
    
    protected $fillable = [
        'nombre',
        'descripcion',
    ];

}
