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
        Schema::table('tratamientos', function (Blueprint $table) {

            // Turno mañana
            $table->time('hora_tm')->nullable();

            // Turno tarde
            $table->time('hora_tt')->nullable();

            // Turno noche
            $table->time('hora_tn')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tratamientos', function (Blueprint $table) {

            $table->dropColumn([
                'hora_tm',
                'hora_tt',
                'hora_tn',
            ]);

        });
    }
};
