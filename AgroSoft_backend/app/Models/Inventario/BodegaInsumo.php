<?php

namespace App\Models\Inventario;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use App\Models\Inventario\Bodega;
use App\Models\Inventario\Insumo;

class BodegaInsumo extends Model
{
     protected $fillable = [
        'bodega_id',
        'insumo_id',
        'cantidad',
    ];

    protected $casts = [
        'cantidad' => 'integer',
    ];

    public function bodega(): BelongsTo
    {
        return $this->belongsTo(Bodega::class, 'bodega_id');
    }

    public function insumo(): BelongsTo
    {
        return $this->belongsTo(Insumo::class, 'insumo_id');
    }

}
