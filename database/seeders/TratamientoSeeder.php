<?php

namespace Database\Seeders;

use App\Models\Tratamiento;
use Illuminate\Database\Seeder;

class TratamientoSeeder extends Seeder
{
    public function run(): void
    {
        Tratamiento::updateOrCreate(
            [
                'paciente_id' => 1,
                'medicamento_id' => 1,
            ],
            [
                'dosis' => '500 mg',
                'frecuencia' => 'Cada 8 horas',
                'via_administracion' => 'Oral',
                'fecha_inicio' => '2026-08-01',
                'fecha_fin' => '2026-08-10',
                'indicaciones' => 'Tomar después de las comidas.',
                'estado' => 'finalizado',
            ]
        );

        Tratamiento::updateOrCreate(
            [
                'paciente_id' => 2,
                'medicamento_id' => 2,
            ],
            [
                'dosis' => '400 mg',
                'frecuencia' => 'Cada 8 horas',
                'via_administracion' => 'Oral',
                'fecha_inicio' => '2026-08-05',
                'fecha_fin' => '2026-08-12',
                'indicaciones' => 'Tomar después de las comidas.',
                'estado' => 'finalizado',
            ]
        );

        Tratamiento::updateOrCreate(
            [
                'paciente_id' => 3,
                'medicamento_id' => 3,
            ],
            [
                'dosis' => '500 mg',
                'frecuencia' => 'Cada 8 horas',
                'via_administracion' => 'Oral',
                'fecha_inicio' => '2026-08-10',
                'fecha_fin' => '2026-08-17',
                'indicaciones' => 'Completar el tratamiento indicado.',
                'estado' => 'activo',
            ]
        );

        Tratamiento::updateOrCreate(
            [
                'paciente_id' => 4,
                'medicamento_id' => 4,
            ],
            [
                'dosis' => '20 mg',
                'frecuencia' => 'Una vez al día',
                'via_administracion' => 'Oral',
                'fecha_inicio' => '2026-08-08',
                'fecha_fin' => null,
                'indicaciones' => 'Tomar antes del desayuno.',
                'estado' => 'activo',
            ]
        );

        Tratamiento::updateOrCreate(
            [
                'paciente_id' => 5,
                'medicamento_id' => 5,
            ],
            [
                'dosis' => '100 mcg',
                'frecuencia' => 'Según necesidad',
                'via_administracion' => 'Inhalatoria',
                'fecha_inicio' => '2026-08-01',
                'fecha_fin' => null,
                'indicaciones' => 'Utilizar según indicación médica.',
                'estado' => 'activo',
            ]
        );
    }
}
