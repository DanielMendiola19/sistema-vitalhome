<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tratamiento extends Model
{
    protected $fillable = [
        'paciente_id',
        'medicamento_id',
        'dosis',
        'frecuencia',
        'via_administracion',
        'fecha_inicio',
        'fecha_fin',
        'indicaciones',
        'estado',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    public function medicamento()
    {
        return $this->belongsTo(Medicamento::class);
    }

    public function kardex()
    {
        return $this->hasOne(Kardex::class);
    }
}
