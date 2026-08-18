<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SignoVital extends Model
{
    protected $table = 'signos_vitales';

    protected $fillable = [
        'paciente_id',
        'fecha_registro',
        'presion_arterial',
        'frecuencia_cardiaca',
        'frecuencia_respiratoria',
        'temperatura',
        'spo2',
    ];

    protected $casts = [
        'fecha_registro' => 'datetime',
        'temperatura' => 'decimal:1',
        'frecuencia_cardiaca' => 'integer',
        'frecuencia_respiratoria' => 'integer',
        'spo2' => 'integer',
    ];

    /**
     * Un signo vital pertenece a un paciente.
     */
    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class, 'paciente_id');
    }
}
