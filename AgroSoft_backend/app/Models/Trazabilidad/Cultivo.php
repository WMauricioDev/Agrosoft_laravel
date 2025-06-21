<?php

namespace App\Models\Trazabilidad;

use Illuminate\Database\Eloquent\Model;

class Cultivo extends Model
{
     protected $table = 'cultivos';
    
    protected $fillable = [
        'especie_id',
        'bancal_id',
        'nombre',
        'unidad_medida_id',
        'activo',
        'fecha_siembra',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'fecha_siembra' => 'date',
    ];

    public function especie()
    {
        return $this->belongsTo(Especie::class, 'especie_id');
    }

    public function bancal()
    {
        return $this->belongsTo(Bancal::class, 'bancal_id');
    }

    public function unidadMedida()
    {
        return $this->belongsTo(\App\Models\Trazabilidad\UnidadMedida::class, 'unidad_medida_id');
    }
}
