<?php

namespace App\Models\Inventario;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use App\Models\Inventario\Bodega;
use App\Models\Inventario\Herramienta;
use App\Models\Usuarios\User;

class BodegaHerramienta extends Model
{
    protected $fillable = [
        'bodega_id',
        'herramienta_id',
        'cantidad',
        'creador_id',
        'costo_total',
        'cantidad_prestada',
    ];

    protected $casts = [
        'cantidad' => 'integer',
        'costo_total' => 'decimal:2',
        'cantidad_prestada' => 'integer',
    ];

    public function bodega(): BelongsTo
    {
        return $this->belongsTo(Bodega::class, 'bodega_id');
    }

    public function herramienta(): BelongsTo
    {
        return $this->belongsTo(Herramienta::class, 'herramienta_id');
    }

    public function creador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creador_id');
    }
}