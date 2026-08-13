<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kardex extends Model
{
    protected $fillable = [
        'medicamento_id',
        'stock_actual',
        'stock_minimo',
        'stock_maximo',
    ];

    public function medicamento()
    {
        return $this->belongsTo(Medicamento::class);
    }

    public function detalles()
    {
        return $this->hasMany(KardexDetalle::class);
    }
}
