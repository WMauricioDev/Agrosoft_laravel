<?php

namespace App\Models\Finanzas;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $table = 'pagos';
    
    protected $fillable = [
        'usuario_id',
        'salario_id',
        'fecha_inicio',
        'fecha_fin',
        'horas_trabajadas',
        'jornales',
        'total_pago',
        'fecha_calculo',
    ];

    protected $casts = [
        'horas_trabajadas' => 'decimal:2',
        'jornales' => 'decimal:2',
        'total_pago' => 'decimal:2',
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'fecha_calculo' => 'datetime',
    ];

    public function usuario()
    {
        return $this->belongsTo(\App\Models\Usuarios\User::class, 'usuario_id');
    }

    public function salario()
    {
        return $this->belongsTo(\App\Models\Finanzas\Salario::class, 'salario_id');
    }

    public function actividades()
    {
        return $this->belongsToMany(\App\Models\Trazabilidad\Actividades::class, 'actividad_pago', 'pago_id', 'actividad_id');
    }

    public function __toString()
    {
        return "Pago por {$this->horas_trabajadas} horas (\${$this->total_pago})";
    }

    protected static function booted()
    {
        static::creating(function ($pago) {
            if (!$pago->horas_trabajadas) {
                $pago->horas_trabajadas = 0;
                $pago->jornales = 0;
                $pago->total_pago = 0;
            }
        });
    }
}