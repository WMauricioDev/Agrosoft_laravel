<?php

namespace App\Models\IoT;

use Illuminate\Database\Eloquent\Model;
use App\Models\Trazabilidad\Bancal;

class Sensor extends Model
{
    protected $table = 'sensors';

    protected $fillable = [
        'nombre',
        'tipo_sensor_id',
        'descripcion',
        'bancal_id',
        'medida_minima',
        'medida_maxima',
        'estado',
        'device_code',
    ];

    protected $casts = [
        'medida_minima' => 'decimal:2',
        'medida_maxima' => 'decimal:2',
    ];

    public function tipoSensor()
    {
        return $this->belongsTo(TipoSensor::class, 'tipo_sensor_id');
    }

    public function bancal()
    {
        return $this->belongsTo(Bancal::class, 'bancal_id');
    }

    public function datosMeteorologicos()
    {
        return $this->hasMany(DatoMeteorologico::class, 'sensor_id');
    }

    public function datosHistoricos()
    {
        return $this->hasMany(DatoHistorico::class, 'sensor_id');
    }

    public function __toString()
    {
        return $this->nombre;
    }

    protected static function booted()
    {
        static::creating(function ($sensor) {
            if (!$sensor->descripcion) {
                $sensor->descripcion = '';
            }
            if (!$sensor->estado) {
                $sensor->estado = 'inactivo';
            }
        });
    }
}