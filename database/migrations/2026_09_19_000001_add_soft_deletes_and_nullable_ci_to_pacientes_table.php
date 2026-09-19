<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // PostgreSQL: permitir varios pacientes sin CI manteniendo UNIQUE para CI reales.
        DB::statement('ALTER TABLE pacientes ALTER COLUMN ci DROP NOT NULL');

        // Normalizar registros antiguos que usaron uno o más ceros como "sin CI".
        DB::statement("UPDATE pacientes SET ci = NULL WHERE ci ~ '^0+$'");

        Schema::table('pacientes', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('pacientes', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        // No se vuelve a NOT NULL automáticamente porque pueden existir pacientes
        // legítimamente registrados sin CI. Hacerlo podría romper el rollback.
    }
};
