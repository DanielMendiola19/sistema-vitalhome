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
        Schema::create('signos_vitales', function (Blueprint $table) {
            $table->id();

            $table->foreignId('paciente_id')
                ->constrained('pacientes')
                ->cascadeOnDelete();

            $table->timestamp('fecha_registro');

            $table->string('presion_arterial', 20);

            $table->unsignedSmallInteger('frecuencia_cardiaca');

            $table->unsignedSmallInteger('frecuencia_respiratoria');

            $table->decimal('temperatura', 4, 1);

            $table->unsignedTinyInteger('spo2');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('signos_vitales');
    }
};
