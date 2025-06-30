<?php

namespace App\Models\Inventario;

use Illuminate\Database\Eloquent\Model;

class Bodega extends Model
{
    protected $fillable = [
        'nombre',
        'telefono',
        'activo',
        'capacidad',
        'ubicacion',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];
}
