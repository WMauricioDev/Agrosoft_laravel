<?php

namespace App\Models\IoT;

use Illuminate\Database\Eloquent\Model;
use App\Models\Trazabilidad\Bancal;

class DatoMeteorologico extends Model
{
    protected $table = 'dato_meteorologicos';

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
        'fecha_medicion',
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
        'fecha_medicion' => 'datetime',
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
        return "Dato Meteorológico de {$this->sensor->nombre} - {$this->fecha_medicion}";
    }

    protected static function booted()
    {
        static::creating(function ($dato) {
            if (!$dato->fecha_medicion) {
                $dato->fecha_medicion = now();
            }
        });
    }
}