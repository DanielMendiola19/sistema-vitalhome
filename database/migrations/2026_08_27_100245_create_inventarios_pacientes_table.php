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
        Schema::create('inventarios_pacientes', function (Blueprint $table) {
            $table->id();

            /*
             * Paciente al que pertenece este inventario.
             */
            $table->foreignId('paciente_id')
                ->constrained('pacientes')
                ->onDelete('cascade');

            /*
             * Medicamento almacenado en el inventario
             * individual del paciente.
             */
            $table->foreignId('medicamento_id')
                ->constrained('medicamentos')
                ->onDelete('restrict');

            /*
             * Cantidad disponible actualmente
             * para el paciente.
             */
            $table->integer('cantidad_actual')
                ->default(0);

            /*
             * Cantidad mínima antes de generar
             * una alerta de stock bajo.
             */
            $table->integer('cantidad_minima')
                ->default(0);

            /*
             * Vencimiento del medicamento que se
             * encuentra actualmente en este inventario.
             */
            $table->date('fecha_vencimiento')
                ->nullable();

            /*
             * Lote del medicamento.
             */
            $table->string('lote', 100)
                ->nullable();

            /*
             * Ubicación física del medicamento.
             */
            $table->string('ubicacion', 150)
                ->nullable();

            /*
             * Estado actual del inventario.
             *
             * Se mantiene para compatibilidad y
             * administración del registro, aunque
             * posteriormente podremos calcular el
             * estado dinámicamente.
             */
            $table->string('estado', 30)
                ->default('disponible');

            $table->timestamps();

            /*
             * Un paciente no puede tener dos registros
             * de inventario para el mismo medicamento.
             */
            $table->unique([
                'paciente_id',
                'medicamento_id',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventarios_pacientes');
    }
};
