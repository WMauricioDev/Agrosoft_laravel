<?php

namespace App\Models\Inventario;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Trazabilidad\unidadMedida;

class Insumo extends Model
{
     protected $fillable = [
        'nombre',
        'descripcion',
        'cantidad',
        'unidad_medida_id',
        'tipo_insumo_id',
        'activo',
        'tipo_empacado',
        'fecha_registro',
        'fecha_caducidad',
        'precio_insumo',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'fecha_registro' => 'datetime',
        'fecha_caducidad' => 'date',
        'precio_insumo' => 'float',
    ];

    public function unidadMedida(): BelongsTo
    {
        return $this->belongsTo(UnidadMedida::class, 'unidad_medida_id');
    }

    public function tipoInsumo(): BelongsTo
    {
        return $this->belongsTo(TipoInsumo::class, 'tipo_insumo_id');
    }
}
