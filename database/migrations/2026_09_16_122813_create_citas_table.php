<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('citas', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | PACIENTE
            |--------------------------------------------------------------------------
            */

            $table->foreignId('paciente_id')
                ->constrained('pacientes')
                ->restrictOnDelete();

            /*
            |--------------------------------------------------------------------------
            | TIPO DE CITA
            |--------------------------------------------------------------------------
            |
            | Valores que utilizaremos:
            |
            | recoger_medicamentos
            | asistir_cita
            |
            */

            $table->string('tipo', 50);

            /*
            |--------------------------------------------------------------------------
            | FECHA Y HORA
            |--------------------------------------------------------------------------
            */

            $table->date('fecha');

            $table->time('hora');

            /*
            |--------------------------------------------------------------------------
            | ESTADO
            |--------------------------------------------------------------------------
            |
            | Valores:
            |
            | pendiente
            | completada
            | cancelada
            |
            */

            $table->string('estado', 30)
                ->default('pendiente');

            /*
            |--------------------------------------------------------------------------
            | OBSERVACIONES
            |--------------------------------------------------------------------------
            */

            $table->text('observaciones')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | USUARIO QUE REGISTRÓ LA CITA
            |--------------------------------------------------------------------------
            */

            $table->foreignId('creado_por')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | FECHAS DE LARAVEL
            |--------------------------------------------------------------------------
            */

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | ÍNDICES
            |--------------------------------------------------------------------------
            */

            $table->index('fecha');

            $table->index('estado');

            $table->index('tipo');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};
