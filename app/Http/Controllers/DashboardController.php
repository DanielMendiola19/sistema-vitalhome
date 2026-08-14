<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\Medicamento;
use App\Models\Tratamiento;
use App\Models\Inventario;

class DashboardController extends Controller
{
    public function index()
    {
        // Pacientes registrados
        $pacientesActivos = Paciente::count();

        // Medicamentos activos
        $medicamentosRegistrados = Medicamento::where('activo', true)->count();

        // Tratamientos activos
        $tratamientosActivos = Tratamiento::where('estado', 'activo')->count();

        // Medicamentos cuyo stock está en el mínimo o por debajo
        $medicamentosPorReponer = Inventario::whereColumn(
            'cantidad_actual',
            '<=',
            'cantidad_minima'
        )->count();

        // Medicamentos que requieren atención
        $medicamentosAtencion = Inventario::with('medicamento')
            ->whereColumn(
                'cantidad_actual',
                '<=',
                'cantidad_minima'
            )
            ->orderBy('cantidad_actual')
            ->limit(5)
            ->get();

        return view('dashboard.index', compact(
            'pacientesActivos',
            'medicamentosRegistrados',
            'tratamientosActivos',
            'medicamentosPorReponer',
            'medicamentosAtencion'
        ));
    }
}
