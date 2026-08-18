<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Paciente extends Model
{
    protected $table = 'pacientes';

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

    protected $casts = [
        'fecha_nacimiento' => 'date',
    ];

    /**
     * Tratamientos del paciente.
     */
    public function tratamientos(): HasMany
    {
        return $this->hasMany(
            Tratamiento::class,
            'paciente_id'
        );
    }

    /**
     * Observaciones clínicas del paciente.
     */
    public function observacionesClinicas(): HasMany
    {
        return $this->hasMany(
            PatientObservation::class,
            'paciente_id'
        );
    }

    /**
     * Alias de observaciones clínicas.
     *
     * Se mantiene para compatibilidad con las vistas
     * y consultas existentes que utilizan "observaciones".
     */
    public function observaciones(): HasMany
    {
        return $this->observacionesClinicas();
    }

    /**
     * Signos vitales del paciente.
     */
    public function signosVitales(): HasMany
    {
        return $this->hasMany(
            SignoVital::class,
            'paciente_id'
        );
    }
}
