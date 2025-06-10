<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Roles extends Model
{
    protected $table = 'roles';

    /** @use HasFactory<\Database\Factories\RolesFactory> */
    use HasFactory;

    protected $fillable = [
        'nombre'
    ];

    public function usuarios()
    {
        return $this->hasMany(User::class, 'rol_id');
    }
}
