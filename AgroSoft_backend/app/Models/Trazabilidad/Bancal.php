<?php

namespace App\Models\Trazabilidad;

use Illuminate\Database\Eloquent\Model;

class Bancal extends Model
{
    protected $table = 'bancals';
    
    protected $fillable = [
        'nombre',
        'tam_x',
        'tam_y',
        'latitud',
        'longitud',
        'lote_id',
    ];
    public function lote()
{
    return $this->belongsTo(\App\Models\Trazabilidad\Lote::class);
}

}
