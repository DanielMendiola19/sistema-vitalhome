<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventarioPaciente extends Model
{
    protected $table = 'inventarios_pacientes';

    protected $fillable = [
        'paciente_id',
        'medicamento_id',
        'cantidad_actual',
        'cantidad_minima',
        'fecha_vencimiento',
        'lote',
        'ubicacion',
        'estado',
    ];

    protected $casts = [
        'cantidad_actual' => 'integer',
        'cantidad_minima' => 'integer',
        'fecha_vencimiento' => 'date',
    ];

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(
            Paciente::class,
            'paciente_id'
        );
    }

    public function medicamento(): BelongsTo
    {
        return $this->belongsTo(
            Medicamento::class,
            'medicamento_id'
        );
    }

    public function movimientos(): HasMany
    {
        return $this->hasMany(
            MovimientoInventario::class,
            'inventario_paciente_id'
        );
    }
}
