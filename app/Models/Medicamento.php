<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicamento extends Model
{
    protected $fillable = [
        'nombre',
        'principio_activo',
        'presentacion',
        'concentracion',
        'unidad_medida',
        'descripcion',
        'activo',
    ];

    public function tratamientos()
    {
        return $this->hasMany(Tratamiento::class);
    }

    public function kardex()
    {
        return $this->hasOne(Kardex::class);
    }

    public function inventario()
    {
        return $this->hasOne(Inventario::class);
    }

}
