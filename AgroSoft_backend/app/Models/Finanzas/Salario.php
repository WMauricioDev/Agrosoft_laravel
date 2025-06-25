<?php

namespace App\Models\Finanzas;

use Illuminate\Database\Eloquent\Model;

class Salario extends Model
{
    protected $table = 'salarios';
    
    protected $fillable = [
        'rol_id',
        'fecha_de_implementacion',
        'valor_jornal',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'fecha_de_implementacion' => 'date',
    ];

    public function rol()
    {
        return $this->belongsTo(\App\Models\Usuarios\Roles::class, 'rol_id');
    }
}
