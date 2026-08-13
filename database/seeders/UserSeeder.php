<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@vitalhome.com'],
            [
                'nombre' => 'Administrador',
                'apellido' => 'Sistema',
                'password' => Hash::make('Admin12345'),
                'rol' => 'administrador',
                'estado' => 'activo',
            ]
        );

        User::updateOrCreate(
            ['email' => 'enfermeria@vitalhome.com'],
            [
                'nombre' => 'Enfermería',
                'apellido' => 'Vital Home',
                'password' => Hash::make('Enfermeria12345'),
                'rol' => 'enfermeria',
                'estado' => 'activo',
            ]
        );
    }
}
