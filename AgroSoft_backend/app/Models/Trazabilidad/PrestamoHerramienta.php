<?php

namespace App\Models\Trazabilidad;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Inventario\BodegaHerramienta;
use App\Models\Inventario\Herramienta;
use App\Models\Trazabilidad\Actividades;

class PrestamoHerramienta extends Model
{
    protected $table = 'prestamos_herramientas';

    protected $fillable = [
        'actividad_id',
        'herramienta_id',
        'bodega_herramienta_id',
        'cantidad_entregada',
        'cantidad_devuelta',
        'entregada',
        'devuelta',
        'fecha_devolucion',
    ];

    protected $casts = [
        'cantidad_entregada' => 'integer',
        'cantidad_devuelta' => 'integer',
        'entregada' => 'boolean',
        'devuelta' => 'boolean',
        'fecha_devolucion' => 'datetime',
    ];

    public function actividad(): BelongsTo
    {
        return $this->belongsTo(Actividades::class, 'actividad_id');
    }

    public function herramienta(): BelongsTo
    {
        return $this->belongsTo(Herramienta::class, 'herramienta_id');
    }

    public function bodegaHerramienta(): BelongsTo
    {
        return $this->belongsTo(BodegaHerramienta::class, 'bodega_herramienta_id');
    }

    public function __toString()
    {
        return "{$this->herramienta->nombre} prestada a {$this->actividad}";
    }
}