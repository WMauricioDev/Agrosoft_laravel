<?php

namespace App\Models\IoT;

use Illuminate\Database\Eloquent\Model;

class TipoSensor extends Model
{
    protected $table = 'tipo_sensores';

    protected $fillable = [
        'nombre',
        'unidad_medida',
        'medida_minima',
        'medida_maxima',
        'descripcion',
    ];

    protected $casts = [
        'medida_minima' => 'decimal:2',
        'medida_maxima' => 'decimal:2',
    ];

    public function sensores()
    {
        return $this->hasMany(Sensor::class, 'tipo_sensor_id');
    }

    public function __toString()
    {
        return $this->nombre;
    }

    protected static function booted()
    {
        static::creating(function ($tipoSensor) {
            if (!$tipoSensor->descripcion) {
                $tipoSensor->descripcion = '';
            }
        });
    }
}