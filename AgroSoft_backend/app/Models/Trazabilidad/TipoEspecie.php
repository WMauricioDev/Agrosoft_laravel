<?php

namespace App\Models\Trazabilidad;

use Illuminate\Database\Eloquent\Model;

class TipoEspecie extends Model
{
    protected $table = 'tipo_especies';
    
    protected $fillable = [
        'nombre',
        'descripcion',
        'img',
    ];
}
