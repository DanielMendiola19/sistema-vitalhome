<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::withTrashed()->updateOrCreate(
            ['email' => 'admin@vitalhome.com'],
            [
                'nombre' => 'Administrador',
                'apellido' => 'Sistema',
                'password' => Hash::make('Admin12345'),
                'rol' => 'administrador',
                'estado' => 'activo',
                'debe_cambiar_password' => false,
            ]
        );

        if ($admin->trashed()) {
            $admin->restore();
        }

        $enfermero = User::withTrashed()->updateOrCreate(
            ['email' => 'enfermeria@vitalhome.com'],
            [
                'nombre' => 'Enfermería',
                'apellido' => 'Vital Home',
                'password' => Hash::make('Enfermeria12345'),
                'rol' => 'enfermero',
                'estado' => 'activo',
                'debe_cambiar_password' => false,
            ]
        );

        if ($enfermero->trashed()) {
            $enfermero->restore();
        }
    }
}
