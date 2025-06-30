<?php

namespace App\Models\Inventario;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Trazabilidad\UnidadMedida;
use App\Models\Trazabilidad\Cosecha;

class PrecioProducto extends Model
{
    protected $fillable = [
        'cosecha_id',
        'unidad_medida_id',
        'precio',
        'fecha_registro',
        'stock',
        'fecha_caducidad',
    ];

    protected $casts = [
        'precio' => 'float',
        'fecha_registro' => 'date',
        'fecha_caducidad' => 'date',
        'stock' => 'integer',
    ];

    public function cosecha(): BelongsTo
    {
        return $this->belongsTo(Cosecha::class, 'cosecha_id');
    }

    public function unidadMedida(): BelongsTo
    {
        return $this->belongsTo(UnidadMedida::class, 'unidad_medida_id');
    }

}
