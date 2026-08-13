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
        Schema::create('movimiento_inventarios', function (Blueprint $table) {
            $table->id();

            $table->foreignId('inventario_id')
                ->constrained('inventarios')
                ->onDelete('cascade');

            $table->dateTime('fecha');
            $table->string('tipo_movimiento', 20);
            $table->integer('cantidad');
            $table->string('motivo', 150)->nullable();
            $table->text('observaciones')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimiento_inventarios');
    }
};
