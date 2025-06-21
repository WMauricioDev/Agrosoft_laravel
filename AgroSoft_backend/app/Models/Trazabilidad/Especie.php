<?php

namespace App\Models\Trazabilidad;

use Illuminate\Database\Eloquent\Model;

class Especie extends Model
{
    protected $table = 'especies';
    
    protected $fillable = [
        'tipo_especie_id',
        'nombre',
        'descripcion',
        'largo_crecimiento',
        'img',
    ];

     public function tipoEspecie()
    {
        return $this->belongsTo(TipoEspecie::class, 'tipo_especie_id');
    }
}
