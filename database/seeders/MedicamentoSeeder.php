<?php

namespace Database\Seeders;

use App\Models\Medicamento;
use Illuminate\Database\Seeder;

class MedicamentoSeeder extends Seeder
{
    public function run(): void
    {
        Medicamento::updateOrCreate(
            ['nombre' => 'Paracetamol'],
            [
                'principio_activo' => 'Paracetamol',
                'presentacion' => 'Tableta',
                'concentracion' => '500 mg',
                'unidad_medida' => 'mg',
                'descripcion' => 'Analgésico y antipirético.',
                'activo' => true,
            ]
        );

        Medicamento::updateOrCreate(
            ['nombre' => 'Ibuprofeno'],
            [
                'principio_activo' => 'Ibuprofeno',
                'presentacion' => 'Tableta',
                'concentracion' => '400 mg',
                'unidad_medida' => 'mg',
                'descripcion' => 'Antiinflamatorio no esteroideo.',
                'activo' => true,
            ]
        );

        Medicamento::updateOrCreate(
            ['nombre' => 'Amoxicilina'],
            [
                'principio_activo' => 'Amoxicilina',
                'presentacion' => 'Cápsula',
                'concentracion' => '500 mg',
                'unidad_medida' => 'mg',
                'descripcion' => 'Antibiótico.',
                'activo' => true,
            ]
        );

        Medicamento::updateOrCreate(
            ['nombre' => 'Omeprazol'],
            [
                'principio_activo' => 'Omeprazol',
                'presentacion' => 'Cápsula',
                'concentracion' => '20 mg',
                'unidad_medida' => 'mg',
                'descripcion' => 'Medicamento utilizado para reducir la producción de ácido gástrico.',
                'activo' => true,
            ]
        );

        Medicamento::updateOrCreate(
            ['nombre' => 'Salbutamol'],
            [
                'principio_activo' => 'Salbutamol',
                'presentacion' => 'Inhalador',
                'concentracion' => '100 mcg',
                'unidad_medida' => 'mcg',
                'descripcion' => 'Broncodilatador.',
                'activo' => true,
            ]
        );
    }
}
