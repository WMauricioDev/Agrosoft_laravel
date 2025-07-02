<?php

namespace App\Models\Trazabilidad;

use Illuminate\Database\Eloquent\Model;
use App\Models\Trazabilidad\Plaga;
use App\Models\Trazabilidad\Cultivo;
use App\Models\Trazabilidad\Bancal;

class Afeccion extends Model
{
    protected $table = 'afecciones';

    protected $fillable = [
        'nombre',
        'descripcion',
        'fecha_deteccion',
        'gravedad',
        'estado',
        'plaga_id',
        'cultivo_id',
        'bancal_id',
    ];

    protected $casts = [
        'fecha_deteccion' => 'date',
        'gravedad' => 'string',
        'estado' => 'string',
    ];

    public function plaga()
    {
        return $this->belongsTo(Plaga::class, 'plaga_id');
    }

    public function cultivo()
    {
        return $this->belongsTo(Cultivo::class, 'cultivo_id');
    }

    public function bancal()
    {
        return $this->belongsTo(Bancal::class, 'bancal_id');
    }
}