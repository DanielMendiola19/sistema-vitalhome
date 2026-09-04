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
        Schema::create('movimientos_inventario', function (Blueprint $table) {
            $table->id();

            /*
             * Medicamento afectado.
             */
            $table->foreignId('medicamento_id')
                ->constrained('medicamentos')
                ->onDelete('restrict');

            /*
             * Inventario general involucrado.
             */
            $table->foreignId('inventario_id')
                ->nullable()
                ->constrained('inventarios')
                ->onDelete('restrict');

            /*
             * Inventario individual del paciente involucrado.
             */
            $table->foreignId('inventario_paciente_id')
                ->nullable()
                ->constrained('inventarios_pacientes')
                ->onDelete('restrict');

            /*
             * Paciente relacionado.
             */
            $table->foreignId('paciente_id')
                ->nullable()
                ->constrained('pacientes')
                ->onDelete('restrict');

            /*
             * Tipo de movimiento:
             *
             * entrada
             * salida
             * transferencia
             */
            $table->string('tipo', 30);

            /*
             * Identificador que permite agrupar
             * varios movimientos pertenecientes
             * a una misma operación.
             */
            $table->uuid('grupo_movimiento')
                ->nullable();

            /*
             * Cantidad involucrada.
             *
             * Siempre positiva.
             */
            $table->unsignedInteger('cantidad');

            /*
             * Stock antes del movimiento.
             */
            $table->unsignedInteger('stock_anterior');

            /*
             * Stock después del movimiento.
             */
            $table->unsignedInteger('stock_posterior');

            /*
             * Lote relacionado.
             */
            $table->string('lote', 100)
                ->nullable();

            /*
             * Motivo o descripción.
             */
            $table->text('motivo')
                ->nullable();

            /*
             * Usuario que realizó la operación.
             */
            $table->foreignId('usuario_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null');

            $table->timestamps();

            /*
             * Índices.
             */
            $table->index([
                'medicamento_id',
                'tipo',
            ]);

            $table->index([
                'paciente_id',
                'created_at',
            ]);

            $table->index([
                'inventario_id',
                'created_at',
            ]);

            $table->index([
                'inventario_paciente_id',
                'created_at',
            ]);

            $table->index('grupo_movimiento');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimientos_inventario');
    }
};
