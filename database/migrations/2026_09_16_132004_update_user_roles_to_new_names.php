<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | USUARIO -> TRABAJO SOCIAL
        |--------------------------------------------------------------------------
        */

        DB::table('users')
            ->where('rol', 'usuario')
            ->update([
                'rol' => 'trabajo_social',
            ]);


        /*
        |--------------------------------------------------------------------------
        | MEDICO -> DOCTOR
        |--------------------------------------------------------------------------
        |
        | Visualmente seguirá mostrándose como "Médico".
        | Internamente utilizamos "doctor" porque así están configurados
        | los permisos y middleware del sistema.
        |
        */

        DB::table('users')
            ->where('rol', 'medico')
            ->update([
                'rol' => 'doctor',
            ]);


        /*
        |--------------------------------------------------------------------------
        | ENFERMERIA -> ENFERMERO
        |--------------------------------------------------------------------------
        |
        | Por compatibilidad con datos antiguos.
        |
        */

        DB::table('users')
            ->where('rol', 'enfermeria')
            ->update([
                'rol' => 'enfermero',
            ]);
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('users')
            ->where('rol', 'trabajo_social')
            ->update([
                'rol' => 'usuario',
            ]);


        DB::table('users')
            ->where('rol', 'doctor')
            ->update([
                'rol' => 'medico',
            ]);


        DB::table('users')
            ->where('rol', 'enfermero')
            ->update([
                'rol' => 'enfermeria',
            ]);
    }
};
