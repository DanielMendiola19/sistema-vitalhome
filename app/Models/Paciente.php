<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Paciente extends Model
{
    use SoftDeletes;
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
        'diagnostico',
        'tiene_seguro',
        'seguro',
        'especialidades',
        'medicamentos_ingreso',
        'estado',
        'motivo_inactividad',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'tiene_seguro' => 'boolean',
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

    /**
     * Inventarios individuales del paciente.
     */
    public function inventariosPacientes(): HasMany
    {
        return $this->hasMany(
            InventarioPaciente::class,
            'paciente_id'
        );
    }

    /**
     * Movimientos de inventario relacionados.
     */
    public function movimientosInventario(): HasMany
    {
        return $this->hasMany(
            MovimientoInventario::class,
            'paciente_id'
        );
    }

    /**
     * Citas del paciente.
     */
    public function citas(): HasMany
    {
        return $this->hasMany(
            Cita::class,
            'paciente_id'
        );
    }
}
