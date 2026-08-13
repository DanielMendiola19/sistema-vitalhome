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
        Schema::create('inventarios', function (Blueprint $table) {
            $table->id();

            $table->foreignId('medicamento_id')
                ->constrained('medicamentos')
                ->onDelete('restrict');

            $table->integer('cantidad_actual')->default(0);
            $table->integer('cantidad_minima')->default(0);
            $table->integer('cantidad_maxima')->nullable();
            $table->date('fecha_vencimiento')->nullable();
            $table->string('lote', 100)->nullable();
            $table->string('ubicacion', 150)->nullable();
            $table->string('estado', 30)->default('disponible');

            $table->timestamps();

            $table->unique('medicamento_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventarios');
    }
};
