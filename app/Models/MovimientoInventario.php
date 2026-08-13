<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovimientoInventario extends Model
{
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
    ];

    public function inventario()
    {
        return $this->belongsTo(Inventario::class);
    }
}
