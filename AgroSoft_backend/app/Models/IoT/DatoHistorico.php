<?php

namespace App\Models\IoT;

use Illuminate\Database\Eloquent\Model;
use App\Models\Trazabilidad\Bancal;

class DatoHistorico extends Model
{
    protected $table = 'dato_historicos';

    protected $fillable = [
        'sensor_id',
        'bancal_id',
        'temperatura',
        'humedad_ambiente',
        'luminosidad',
        'lluvia',
        'velocidad_viento',
        'direccion_viento',
        'humedad_suelo',
        'ph_suelo',
        'fecha_promedio',
        'cantidad_mediciones',
    ];

    protected $casts = [
        'temperatura' => 'decimal:2',
        'humedad_ambiente' => 'decimal:2',
        'luminosidad' => 'decimal:2',
        'lluvia' => 'decimal:2',
        'velocidad_viento' => 'decimal:2',
        'direccion_viento' => 'decimal:0',
        'humedad_suelo' => 'decimal:2',
        'ph_suelo' => 'decimal:2',
        'fecha_promedio' => 'datetime',
        'cantidad_mediciones' => 'integer',
    ];

    public function sensor()
    {
        return $this->belongsTo(Sensor::class, 'sensor_id');
    }

    public function bancal()
    {
        return $this->belongsTo(Bancal::class, 'bancal_id');
    }

    public function __toString()
    {
        return "Promedio de {$this->sensor->nombre} - {$this->fecha_promedio}";
    }
}