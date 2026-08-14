<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MovimientoInventario extends Model
{
    protected $table = 'movimiento_inventarios';

    protected $fillable = [
        'inventario_id',
        'fecha',
        'tipo_movimiento',
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
        return $this->belongsTo(Inventario::class);
    }
}
