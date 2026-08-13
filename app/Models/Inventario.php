<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    protected $fillable = [
        'medicamento_id',
        'cantidad_actual',
        'cantidad_minima',
        'cantidad_maxima',
        'fecha_vencimiento',
        'lote',
        'ubicacion',
        'estado',
    ];

    protected $casts = [
        'fecha_vencimiento' => 'date',
    ];

    public function medicamento()
    {
        return $this->belongsTo(Medicamento::class);
    }

    public function movimientos()
    {
        return $this->hasMany(MovimientoInventario::class);
    }
}
