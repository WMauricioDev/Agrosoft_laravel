<?php

namespace App\Models\Trazabilidad;

use Illuminate\Database\Eloquent\Model;

class Cosecha extends Model
{
     protected $table = 'cosechas';
    
    protected $fillable = [
        'cultivo_id',
        'cantidad',
        'unidad_medida_id',
        'fecha',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function cultivo()
    {
        return $this->belongsTo(Cultivo::class, 'cultivo_id');
    }

    public function unidadMedida()
    {
        return $this->belongsTo(\App\Models\Trazabilidad\UnidadMedida::class, 'unidad_medida_id');
    }

}
