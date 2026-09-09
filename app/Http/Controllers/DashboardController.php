<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\Medicamento;
use App\Models\Tratamiento;
use App\Models\Inventario;
use App\Models\MovimientoInventario;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        Carbon::setLocale('es');
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



        $actividadReciente = MovimientoInventario::with([
            'inventario.medicamento',
            'inventarioPaciente.medicamento',
            'paciente',
            'usuario',
        ])
            ->latest('fecha')
            ->limit(5)
            ->get();


        // Alertas de inventario
        $medicamentosAgotados = Inventario::with('medicamento')
            ->where('cantidad_actual', 0)
            ->orderBy('updated_at', 'desc')
            ->get();

        $medicamentosStockBajo = Inventario::with('medicamento')
            ->where('cantidad_actual', '>', 0)
            ->whereColumn(
                'cantidad_actual',
                '<=',
                'cantidad_minima'
            )
            ->orderBy('cantidad_actual')
            ->get();

        // Medicamentos próximos a vencer
        $fechaLimiteVencimiento = Carbon::today()->addDays(30);

        $medicamentosPorVencer = Inventario::with('medicamento')
            ->whereNotNull('fecha_vencimiento')
            ->whereDate('fecha_vencimiento', '>=', Carbon::today())
            ->whereDate('fecha_vencimiento', '<=', $fechaLimiteVencimiento)
            ->orderBy('fecha_vencimiento')
            ->get();

        // Medicamentos vencidos
        $medicamentosVencidos = Inventario::with('medicamento')
            ->whereNotNull('fecha_vencimiento')
            ->whereDate('fecha_vencimiento', '<', Carbon::today())
            ->orderBy('fecha_vencimiento')
            ->get();

        return view('dashboard.index', compact(
            'pacientesActivos',
            'medicamentosRegistrados',
            'tratamientosActivos',
            'medicamentosPorReponer',
            'medicamentosAtencion',
            'actividadReciente',
            'medicamentosAgotados',
            'medicamentosStockBajo',
            'medicamentosPorVencer',
            'medicamentosVencidos'
        ));
    }
}
