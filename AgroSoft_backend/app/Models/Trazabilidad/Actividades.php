<?php

namespace App\Models\Trazabilidad;

use App\Models\Usuarios\User;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Actividades extends Model
{
    protected $table = 'actividades';

    protected $fillable = [
        'tipo_actividad_id',
        'descripcion',
        'fecha_inicio',
        'fecha_fin',
        'cultivo_id',
        'estado',
        'prioridad',
        'instrucciones_adicionales',
    ];

    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
        'estado' => 'string',
        'prioridad' => 'string',
    ];

    protected $attributes = [
        'estado' => 'PENDIENTE',
        'prioridad' => 'MEDIA',
    ];

    public const ESTADO_CHOICES = ['PENDIENTE', 'EN_PROCESO', 'COMPLETADA', 'CANCELADA'];
    public const PRIORIDAD_CHOICES = ['ALTA', 'MEDIA', 'BAJA'];

    public function tipoActividad(): BelongsTo
    {
        return $this->belongsTo(TipoActividad::class, 'tipo_actividad_id');
    }

    public function cultivo(): BelongsTo
    {
        return $this->belongsTo(Cultivo::class, 'cultivo_id');
    }

    public function usuarios(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'actividad_usuarios', 'actividad_id', 'user_id');
    }

    public function prestamosInsumos(): HasMany
    {
        return $this->hasMany(PrestamoInsumo::class, 'actividad_id');
    }

    public function prestamosHerramientas(): HasMany
    {
        return $this->hasMany(PrestamoHerramienta::class, 'actividad_id');
    }

    public function __toString()
    {
        return "{$this->tipoActividad->nombre} - {$this->estado}";
    }

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if (!in_array($model->estado, self::ESTADO_CHOICES)) {
                throw new \Exception("Estado inválido: {$model->estado}");
            }
            if (!in_array($model->prioridad, self::PRIORIDAD_CHOICES)) {
                throw new \Exception("Prioridad inválida: {$model->prioridad}");
            }
        });
    }
}
