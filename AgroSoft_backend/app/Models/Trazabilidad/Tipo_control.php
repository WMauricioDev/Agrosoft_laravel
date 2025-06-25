<?php

namespace App\Models\Trazabilidad;

use Illuminate\Database\Eloquent\Model;

class Tipo_Control extends Model
{
    protected $table = 'tipo_controles';

    protected $fillable=[
        'nombre',
        'descripcion'
    ];
}
