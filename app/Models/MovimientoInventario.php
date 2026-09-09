<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MovimientoInventario extends Model
{
    protected $table = 'movimiento_inventarios';

    protected $fillable = [
        'inventario_id',
        'inventario_paciente_id',
        'paciente_id',
        'usuario_id',
        'fecha',
        'tipo_movimiento',
        'grupo_movimiento',
        'cantidad',
        'motivo',
        'observaciones',
    ];

    protected $casts = [
        'fecha' => 'datetime',
        'cantidad' => 'integer',
    ];

    public function inventario(): BelongsTo
    {
        return $this->belongsTo(
            Inventario::class,
            'inventario_id'
        );
    }

    public function inventarioPaciente(): BelongsTo
    {
        return $this->belongsTo(
            InventarioPaciente::class,
            'inventario_paciente_id'
        );
    }

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(
            Paciente::class,
            'paciente_id'
        );
    }
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'usuario_id'
        );
    }
}
