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
        'hora_tm',
        'hora_tt',
        'hora_tn',
        'indicaciones',
        'estado',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'hora_tm' => 'datetime:H:i',
        'hora_tt' => 'datetime:H:i',
        'hora_tn' => 'datetime:H:i',
    ];

    /**
     * Paciente al que pertenece el tratamiento.
     */
    public function paciente(): BelongsTo
    {
        return $this->belongsTo(
            Paciente::class,
            'paciente_id'
        );
    }

    /**
     * Medicamento utilizado en el tratamiento.
     */
    public function medicamento(): BelongsTo
    {
        return $this->belongsTo(
            Medicamento::class,
            'medicamento_id'
        );
    }
}
