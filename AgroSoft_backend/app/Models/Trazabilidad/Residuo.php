<?php

namespace App\Models\Trazabilidad;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Residuo extends Model
{
    use HasFactory;

    protected $table = 'residuos'; // asegúrate que este es el nombre de tu tabla
    protected $fillable = [
        'id_cosecha',
        'id_tipo_residuo',
        'nombre',
        'descripcion',
        'fecha',
        'cantidad'
    ];

    // Relación con TipoResiduo
    public function tipoResiduo()
    {
        return $this->belongsTo(TipoResiduo::class, 'id_tipo_residuo');
    }

    // Relación con Cosecha
    public function cosecha()
    {
        return $this->belongsTo(Cosecha::class, 'id_cosecha');
    }
}
