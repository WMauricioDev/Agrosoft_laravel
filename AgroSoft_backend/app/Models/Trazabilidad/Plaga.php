<?php

namespace App\Models\Trazabilidad;

use Illuminate\Database\Eloquent\Model;
use App\Models\Trazabilidad\TipoPlaga;

class Plaga extends Model
{
    protected $table = 'plagas';

    protected $fillable = [
        'fk_tipo_plaga',
        'nombre',
        'descripcion',
        'img',
    ];

    // Relación con TipoPlaga
    public function tipoPlaga()
    {
        return $this->belongsTo(TipoPlaga::class, 'fk_tipo_plaga');
    }
}