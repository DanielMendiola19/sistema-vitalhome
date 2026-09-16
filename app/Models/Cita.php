<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | CAMPOS ASIGNABLES
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'paciente_id',
        'tipo',
        'fecha',
        'hora',
        'estado',
        'observaciones',
        'creado_por',
    ];


    /*
    |--------------------------------------------------------------------------
    | CASTS
    |--------------------------------------------------------------------------
    */

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | RELACIÓN CON PACIENTE
    |--------------------------------------------------------------------------
    */

    public function paciente()
    {
        return $this->belongsTo(
            Paciente::class,
            'paciente_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | USUARIO QUE REGISTRÓ LA CITA
    |--------------------------------------------------------------------------
    */

    public function creador()
    {
        return $this->belongsTo(
            User::class,
            'creado_por'
        );
    }
}
