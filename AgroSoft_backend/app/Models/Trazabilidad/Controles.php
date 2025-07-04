<?php

namespace App\Models\Trazabilidad;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\models\Trazabilidad\Tipo_Control;
use App\models\Inventario\Insumo;
use App\models\Usuarios\User;
class Controles extends Model
{
    use HasFactory;

    protected $table = 'controles';

    protected $fillable = [
        'afeccion_id',
        'tipo_control_id',
        'producto_id',
        'descripcion',
        'fecha_control',
        'responsable_id',
        'efectividad',
        'observaciones'
    ];

    // Relaciones
    public function afeccion()
    {
        return $this->belongsTo(Afeccion::class);
    }

    public function tipo_control()
    {
        return $this->belongsTo(Tipo_Control::class);
    }

    public function producto()
    {
        return $this->belongsTo(Insumo::class, 'producto_id');
    }

    public function responsable()
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }
}
