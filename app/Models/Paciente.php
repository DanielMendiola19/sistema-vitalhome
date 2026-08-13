<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    protected $fillable = [
        'nombre',
        'apellido',
        'ci',
        'fecha_nacimiento',
        'sexo',
        'telefono',
        'direccion',
        'observaciones',
    ];
    public function tratamientos()
    {
        return $this->hasMany(Tratamiento::class);
    }
}
