<?php

namespace App\Models\Inventario;

use Illuminate\Database\Eloquent\Model;

class Herramienta extends Model
{
    protected $table = 'herramientas';

    protected $fillable = [
        'nombre',
        'descripcion',
        'cantidad',
        'estado',
        'activo',
        'fecha_registro',
        'precio',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'fecha_registro' => 'datetime',
        'precio' => 'decimal:2',
    ];

    public function __toString()
    {
        return $this->nombre;
    }
}