<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KardexDetalle extends Model
{
    protected $table = 'kardex_detalles';

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
        'cantidad' => 'integer',
        'saldo' => 'integer',
    ];

    public function kardex(): BelongsTo
    {
        return $this->belongsTo(Kardex::class);
    }
}
