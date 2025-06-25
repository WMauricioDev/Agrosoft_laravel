<?php

namespace App\Models\Trazabilidad;

use Illuminate\Database\Eloquent\Model;

class TipoPlaga extends Model
{
    protected $table = 'tipo_plagas';
    
    protected $fillable = [
        'nombre',
        'descripcion',
        'img',
    ];
}
