<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tratamiento extends Model
{
    protected $table = 'tratamientos';

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

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class, 'paciente_id');
    }

    public function medicamento(): BelongsTo
    {
        return $this->belongsTo(Medicamento::class, 'medicamento_id');
    }
}
