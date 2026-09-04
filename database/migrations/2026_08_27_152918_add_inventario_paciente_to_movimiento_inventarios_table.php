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
        Schema::table('movimiento_inventarios', function (Blueprint $table) {

            /*
             * Inventario individual del paciente.
             *
             * Es nullable porque los movimientos del
             * inventario general no necesitan paciente.
             */
            $table->foreignId('inventario_paciente_id')
                ->nullable()
                ->after('inventario_id')
                ->constrained('inventarios_pacientes')
                ->onDelete('cascade');

            /*
             * Paciente relacionado con el movimiento.
             *
             * Es nullable porque una entrada o salida
             * del inventario general puede no estar
             * relacionada con ningún paciente.
             */
            $table->foreignId('paciente_id')
                ->nullable()
                ->after('inventario_paciente_id')
                ->constrained('pacientes')
                ->onDelete('cascade');

            /*
             * Permite agrupar varios movimientos que
             * forman parte de una misma operación.
             *
             * Ejemplo:
             *
             * Transferencia general -> paciente
             *
             * Movimiento 1: -20 general
             * Movimiento 2: +20 paciente
             *
             * Ambos tendrán el mismo grupo_movimiento.
             */
            $table->uuid('grupo_movimiento')
                ->nullable()
                ->after('tipo_movimiento');

            /*
             * Índices para acelerar consultas del historial.
             */
            $table->index('inventario_paciente_id');

            $table->index('paciente_id');

            $table->index('grupo_movimiento');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('movimiento_inventarios', function (Blueprint $table) {

            $table->dropForeign([
                'inventario_paciente_id',
            ]);

            $table->dropForeign([
                'paciente_id',
            ]);

            $table->dropIndex([
                'inventario_paciente_id',
            ]);

            $table->dropIndex([
                'paciente_id',
            ]);

            $table->dropIndex([
                'grupo_movimiento',
            ]);

            $table->dropColumn([
                'inventario_paciente_id',
                'paciente_id',
                'grupo_movimiento',
            ]);
        });
    }
};
