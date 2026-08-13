<?php

namespace Database\Seeders;

use App\Models\Paciente;
use Illuminate\Database\Seeder;

class PacienteSeeder extends Seeder
{
    public function run(): void
    {
        Paciente::updateOrCreate(
            ['ci' => '12345678'],
            [
                'nombre' => 'Juan',
                'apellido' => 'Pérez',
                'fecha_nacimiento' => '1985-03-15',
                'sexo' => 'Masculino',
                'telefono' => '70000001',
                'direccion' => 'Ciudad Satélite, El Alto',
                'observaciones' => 'Paciente de prueba para desarrollo.',
            ]
        );

        Paciente::updateOrCreate(
            ['ci' => '23456789'],
            [
                'nombre' => 'María',
                'apellido' => 'López',
                'fecha_nacimiento' => '1990-07-22',
                'sexo' => 'Femenino',
                'telefono' => '70000002',
                'direccion' => 'Villa Adela, El Alto',
                'observaciones' => 'Paciente de prueba para desarrollo.',
            ]
        );

        Paciente::updateOrCreate(
            ['ci' => '34567890'],
            [
                'nombre' => 'Carlos',
                'apellido' => 'Fernández',
                'fecha_nacimiento' => '1978-11-08',
                'sexo' => 'Masculino',
                'telefono' => '70000003',
                'direccion' => 'Sopocachi, La Paz',
                'observaciones' => 'Paciente de prueba para desarrollo.',
            ]
        );

        Paciente::updateOrCreate(
            ['ci' => '45678901'],
            [
                'nombre' => 'Ana',
                'apellido' => 'García',
                'fecha_nacimiento' => '1995-01-30',
                'sexo' => 'Femenino',
                'telefono' => '70000004',
                'direccion' => 'Miraflores, La Paz',
                'observaciones' => 'Paciente de prueba para desarrollo.',
            ]
        );

        Paciente::updateOrCreate(
            ['ci' => '56789012'],
            [
                'nombre' => 'Luis',
                'apellido' => 'Martínez',
                'fecha_nacimiento' => '1982-09-17',
                'sexo' => 'Masculino',
                'telefono' => '70000005',
                'direccion' => 'Villa Fátima, La Paz',
                'observaciones' => 'Paciente de prueba para desarrollo.',
            ]
        );
    }
}
