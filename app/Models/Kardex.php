<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kardex extends Model
{
    protected $table = 'kardexes';

    protected $fillable = [
        'medicamento_id',
        'stock_actual',
        'stock_minimo',
        'stock_maximo',
    ];

    protected $casts = [
        'stock_actual' => 'integer',
        'stock_minimo' => 'integer',
        'stock_maximo' => 'integer',
    ];

    public function medicamento(): BelongsTo
    {
        return $this->belongsTo(Medicamento::class);
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(KardexDetalle::class);
    }
}
