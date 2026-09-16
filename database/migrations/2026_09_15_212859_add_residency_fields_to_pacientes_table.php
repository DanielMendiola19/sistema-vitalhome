<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pacientes', function (Blueprint $table) {
            $table->boolean('tiene_seguro')->default(false);
            $table->string('seguro', 150)->nullable();
            $table->text('especialidades')->nullable();
            $table->text('medicamentos_ingreso')->nullable();
            $table->string('estado', 20)->default('activo');
            $table->string('motivo_inactividad', 30)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('pacientes', function (Blueprint $table) {
            $table->dropColumn([
                'tiene_seguro',
                'seguro',
                'especialidades',
                'medicamentos_ingreso',
                'estado',
                'motivo_inactividad',
            ]);
        });
    }
};
