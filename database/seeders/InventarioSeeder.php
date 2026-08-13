<?php

namespace Database\Seeders;

use App\Models\Inventario;
use Illuminate\Database\Seeder;

class InventarioSeeder extends Seeder
{
    public function run(): void
    {
        Inventario::updateOrCreate(
            ['medicamento_id' => 1],
            [
                'cantidad_actual' => 90,
                'cantidad_minima' => 20,
                'cantidad_maxima' => 200,
                'fecha_vencimiento' => '2027-08-01',
                'lote' => 'PAR-2026-001',
                'ubicacion' => 'Almacén principal - Estante A1',
                'estado' => 'disponible',
            ]
        );

        Inventario::updateOrCreate(
            ['medicamento_id' => 2],
            [
                'cantidad_actual' => 75,
                'cantidad_minima' => 20,
                'cantidad_maxima' => 150,
                'fecha_vencimiento' => '2027-06-15',
                'lote' => 'IBU-2026-001',
                'ubicacion' => 'Almacén principal - Estante A2',
                'estado' => 'disponible',
            ]
        );

        Inventario::updateOrCreate(
            ['medicamento_id' => 3],
            [
                'cantidad_actual' => 50,
                'cantidad_minima' => 10,
                'cantidad_maxima' => 100,
                'fecha_vencimiento' => '2027-03-20',
                'lote' => 'AMO-2026-001',
                'ubicacion' => 'Almacén principal - Estante A3',
                'estado' => 'disponible',
            ]
        );

        Inventario::updateOrCreate(
            ['medicamento_id' => 4],
            [
                'cantidad_actual' => 60,
                'cantidad_minima' => 15,
                'cantidad_maxima' => 120,
                'fecha_vencimiento' => '2027-09-10',
                'lote' => 'OME-2026-001',
                'ubicacion' => 'Almacén principal - Estante B1',
                'estado' => 'disponible',
            ]
        );

        Inventario::updateOrCreate(
            ['medicamento_id' => 5],
            [
                'cantidad_actual' => 40,
                'cantidad_minima' => 10,
                'cantidad_maxima' => 80,
                'fecha_vencimiento' => '2027-05-25',
                'lote' => 'SAL-2026-001',
                'ubicacion' => 'Almacén principal - Estante B2',
                'estado' => 'disponible',
            ]
        );
    }
}
