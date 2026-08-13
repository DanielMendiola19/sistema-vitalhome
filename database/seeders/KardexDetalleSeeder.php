<?php

namespace Database\Seeders;

use App\Models\KardexDetalle;
use Illuminate\Database\Seeder;

class KardexDetalleSeeder extends Seeder
{
    public function run(): void
    {
        // Paracetamol - Kardex 1
        KardexDetalle::updateOrCreate(
            [
                'kardex_id' => 1,
                'fecha' => '2026-08-01 08:00:00',
                'tipo_movimiento' => 'entrada',
            ],
            [
                'cantidad' => 100,
                'saldo' => 100,
                'motivo' => 'Ingreso inicial',
                'observaciones' => 'Ingreso de stock inicial.',
            ]
        );

        KardexDetalle::updateOrCreate(
            [
                'kardex_id' => 1,
                'fecha' => '2026-08-05 10:00:00',
                'tipo_movimiento' => 'salida',
            ],
            [
                'cantidad' => 20,
                'saldo' => 80,
                'motivo' => 'Entrega a paciente',
                'observaciones' => 'Salida por tratamiento.',
            ]
        );

        KardexDetalle::updateOrCreate(
            [
                'kardex_id' => 1,
                'fecha' => '2026-08-10 09:00:00',
                'tipo_movimiento' => 'entrada',
            ],
            [
                'cantidad' => 30,
                'saldo' => 110,
                'motivo' => 'Reposición de stock',
                'observaciones' => 'Ingreso por reposición.',
            ]
        );

        KardexDetalle::updateOrCreate(
            [
                'kardex_id' => 1,
                'fecha' => '2026-08-12 15:00:00',
                'tipo_movimiento' => 'salida',
            ],
            [
                'cantidad' => 20,
                'saldo' => 90,
                'motivo' => 'Entrega a paciente',
                'observaciones' => 'Salida por tratamiento.',
            ]
        );

        // Ibuprofeno - Kardex 2
        KardexDetalle::updateOrCreate(
            [
                'kardex_id' => 2,
                'fecha' => '2026-08-02 08:00:00',
                'tipo_movimiento' => 'entrada',
            ],
            [
                'cantidad' => 100,
                'saldo' => 100,
                'motivo' => 'Ingreso inicial',
                'observaciones' => 'Ingreso de stock inicial.',
            ]
        );

        KardexDetalle::updateOrCreate(
            [
                'kardex_id' => 2,
                'fecha' => '2026-08-08 11:00:00',
                'tipo_movimiento' => 'salida',
            ],
            [
                'cantidad' => 25,
                'saldo' => 75,
                'motivo' => 'Entrega a paciente',
                'observaciones' => 'Salida por tratamiento.',
            ]
        );

        // Amoxicilina - Kardex 3
        KardexDetalle::updateOrCreate(
            [
                'kardex_id' => 3,
                'fecha' => '2026-08-03 08:00:00',
                'tipo_movimiento' => 'entrada',
            ],
            [
                'cantidad' => 70,
                'saldo' => 70,
                'motivo' => 'Ingreso inicial',
                'observaciones' => 'Ingreso de stock inicial.',
            ]
        );

        KardexDetalle::updateOrCreate(
            [
                'kardex_id' => 3,
                'fecha' => '2026-08-11 14:00:00',
                'tipo_movimiento' => 'salida',
            ],
            [
                'cantidad' => 20,
                'saldo' => 50,
                'motivo' => 'Entrega a paciente',
                'observaciones' => 'Salida por tratamiento.',
            ]
        );

        // Omeprazol - Kardex 4
        KardexDetalle::updateOrCreate(
            [
                'kardex_id' => 4,
                'fecha' => '2026-08-04 08:00:00',
                'tipo_movimiento' => 'entrada',
            ],
            [
                'cantidad' => 80,
                'saldo' => 80,
                'motivo' => 'Ingreso inicial',
                'observaciones' => 'Ingreso de stock inicial.',
            ]
        );

        KardexDetalle::updateOrCreate(
            [
                'kardex_id' => 4,
                'fecha' => '2026-08-09 12:00:00',
                'tipo_movimiento' => 'salida',
            ],
            [
                'cantidad' => 20,
                'saldo' => 60,
                'motivo' => 'Entrega a paciente',
                'observaciones' => 'Salida por tratamiento.',
            ]
        );

        // Salbutamol - Kardex 5
        KardexDetalle::updateOrCreate(
            [
                'kardex_id' => 5,
                'fecha' => '2026-08-01 08:00:00',
                'tipo_movimiento' => 'entrada',
            ],
            [
                'cantidad' => 50,
                'saldo' => 50,
                'motivo' => 'Ingreso inicial',
                'observaciones' => 'Ingreso de stock inicial.',
            ]
        );

        KardexDetalle::updateOrCreate(
            [
                'kardex_id' => 5,
                'fecha' => '2026-08-07 16:00:00',
                'tipo_movimiento' => 'salida',
            ],
            [
                'cantidad' => 10,
                'saldo' => 40,
                'motivo' => 'Entrega a paciente',
                'observaciones' => 'Salida por tratamiento.',
            ]
        );
    }
}
