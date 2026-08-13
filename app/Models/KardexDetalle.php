<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KardexDetalle extends Model
{
    protected $fillable = [
        'kardex_id',
        'fecha',
        'tipo_movimiento',
        'cantidad',
        'saldo',
        'motivo',
        'observaciones',
    ];

    protected $casts = [
        'fecha' => 'datetime',
    ];

    public function kardex()
    {
        return $this->belongsTo(Kardex::class);
    }
}
