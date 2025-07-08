<?php

namespace App\Models\Trazabilidad;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoResiduo extends Model
{
    use HasFactory;

    protected $table = 'tipo_residuos'; 
    protected $fillable = [
        'nombre',
        'descripcion'
    ];

    public function residuos()
    {
        return $this->hasMany(Residuo::class, 'id_tipo_residuo');
    }
}
