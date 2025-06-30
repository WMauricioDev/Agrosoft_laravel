<?php

namespace App\Models\Inventario;

use Illuminate\Database\Eloquent\Model;

class TipoInsumo extends Model
{
     protected $fillable = [
        'nombre',
        'descripcion',
        'creada_por_usuario',
        'fecha_creacion',
    ];

    protected $casts = [
        'creada_por_usuario' => 'boolean',
        'fecha_creacion' => 'datetime',
    ];
}
