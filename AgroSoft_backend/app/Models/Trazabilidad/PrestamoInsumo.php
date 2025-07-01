<?php

namespace App\Models\Trazabilidad;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Inventario\Insumo;
use App\Models\Trazabilidad\Actividades;

class PrestamoInsumo extends Model
{
    protected $table = 'prestamos_insumos';

    protected $fillable = [
        'actividad_id',
        'insumo_id',
        'cantidad_usada',
        'cantidad_devuelta',
        'fecha_devolucion',
        'unidad_medida_id',
    ];

    protected $casts = [
        'cantidad_usada' => 'integer',
        'cantidad_devuelta' => 'integer',
        'fecha_devolucion' => 'datetime',
    ];

    public function actividad(): BelongsTo
    {
        return $this->belongsTo(Actividades::class, 'actividad_id');
    }

    public function insumo(): BelongsTo
    {
        return $this->belongsTo(Insumo::class, 'insumo_id');
    }

    public function unidadMedida(): BelongsTo
    {
        return $this->belongsTo(UnidadMedida::class, 'unidad_medida_id');
    }

}
