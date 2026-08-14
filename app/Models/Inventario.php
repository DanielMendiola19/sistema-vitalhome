<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inventario extends Model
{
    protected $table = 'inventarios';

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
        'cantidad_actual' => 'integer',
        'cantidad_minima' => 'integer',
        'cantidad_maxima' => 'integer',
    ];

    public function medicamento(): BelongsTo
    {
        return $this->belongsTo(Medicamento::class);
    }
}
