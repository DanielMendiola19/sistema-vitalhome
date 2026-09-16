<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CitaController extends Controller
{
    /**
     * Mostrar todas las citas.
     */
    public function index(Request $request)
    {
        $query = Cita::with([
            'paciente',
            'creador'
        ]);

        /*
        |--------------------------------------------------------------------------
        | FILTRO POR FECHA
        |--------------------------------------------------------------------------
        */

        if ($request->filled('fecha')) {
            $query->whereDate(
                'fecha',
                $request->fecha
            );
        }

        /*
        |--------------------------------------------------------------------------
        | FILTRO POR TIPO
        |--------------------------------------------------------------------------
        */

        if ($request->filled('tipo')) {
            $query->where(
                'tipo',
                $request->tipo
            );
        }

        /*
        |--------------------------------------------------------------------------
        | FILTRO POR ESTADO
        |--------------------------------------------------------------------------
        */

        if ($request->filled('estado')) {
            $query->where(
                'estado',
                $request->estado
            );
        }

        /*
        |--------------------------------------------------------------------------
        | ORDEN
        |--------------------------------------------------------------------------
        */

        $citas = $query
            ->orderBy('fecha')
            ->orderBy('hora')
            ->get();

        return view(
            'citas.index',
            compact('citas')
        );
    }


    /**
     * Mostrar formulario para crear una cita.
     */
    public function create()
    {
        $pacientes = Paciente::query()
            ->where('estado', 'activo')
            ->orderBy('nombre')
            ->orderBy('apellido')
            ->get();

        return view(
            'citas.create',
            compact('pacientes')
        );
    }


    /**
     * Guardar una nueva cita.
     */
    public function store(Request $request)
    {
        $datos = $request->validate([
            'paciente_id' => [
                'required',
                'exists:pacientes,id',
            ],

            'tipo' => [
                'required',
                'in:recoger_medicamentos,asistir_cita',
            ],

            'fecha' => [
                'required',
                'date',
            ],

            'hora' => [
                'required',
                'date_format:H:i',
            ],

            'observaciones' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | EVITAR DUPLICADOS EXACTOS
        |--------------------------------------------------------------------------
        */

        $existe = Cita::query()
            ->where('paciente_id', $datos['paciente_id'])
            ->where('fecha', $datos['fecha'])
            ->where('hora', $datos['hora'])
            ->where('estado', '!=', 'cancelada')
            ->exists();

        if ($existe) {
            return back()
                ->withInput()
                ->withErrors([
                    'hora' => 'Este paciente ya tiene una cita registrada en esa fecha y hora.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | CREAR CITA
        |--------------------------------------------------------------------------
        */

        Cita::create([
            'paciente_id' => $datos['paciente_id'],
            'tipo' => $datos['tipo'],
            'fecha' => $datos['fecha'],
            'hora' => $datos['hora'],
            'estado' => 'pendiente',
            'observaciones' => $datos['observaciones'] ?? null,
            'creado_por' => Auth::id(),
        ]);

        return redirect()
            ->route('citas.index')
            ->with(
                'success',
                'Cita registrada correctamente.'
            );
    }


    /**
     * Mostrar formulario para editar una cita.
     */
    public function edit(Cita $cita)
    {
        $pacientes = Paciente::query()
            ->where('estado', 'activo')
            ->orderBy('nombre')
            ->orderBy('apellido')
            ->get();

        return view(
            'citas.edit',
            compact(
                'cita',
                'pacientes'
            )
        );
    }


    /**
     * Actualizar una cita.
     */
    public function update(
        Request $request,
        Cita $cita
    ) {
        $datos = $request->validate([
            'paciente_id' => [
                'required',
                'exists:pacientes,id',
            ],

            'tipo' => [
                'required',
                'in:recoger_medicamentos,asistir_cita',
            ],

            'fecha' => [
                'required',
                'date',
            ],

            'hora' => [
                'required',
                'date_format:H:i',
            ],

            'observaciones' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | EVITAR DUPLICADOS AL EDITAR
        |--------------------------------------------------------------------------
        */

        $existe = Cita::query()
            ->where('paciente_id', $datos['paciente_id'])
            ->where('fecha', $datos['fecha'])
            ->where('hora', $datos['hora'])
            ->where('estado', '!=', 'cancelada')
            ->where('id', '!=', $cita->id)
            ->exists();

        if ($existe) {
            return back()
                ->withInput()
                ->withErrors([
                    'hora' => 'Este paciente ya tiene otra cita registrada en esa fecha y hora.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | ACTUALIZAR
        |--------------------------------------------------------------------------
        */

        $cita->update([
            'paciente_id' => $datos['paciente_id'],
            'tipo' => $datos['tipo'],
            'fecha' => $datos['fecha'],
            'hora' => $datos['hora'],
            'observaciones' => $datos['observaciones'] ?? null,
        ]);

        return redirect()
            ->route('citas.index')
            ->with(
                'success',
                'Cita actualizada correctamente.'
            );
    }


    /**
     * Marcar una cita como completada.
     */
    public function completar(Cita $cita)
    {
        if ($cita->estado === 'cancelada') {
            return back()->with(
                'error',
                'No se puede completar una cita cancelada.'
            );
        }

        $cita->update([
            'estado' => 'completada',
        ]);

        return back()->with(
            'success',
            'Cita marcada como completada.'
        );
    }


    /**
     * Cancelar una cita.
     */
    public function cancelar(Cita $cita)
    {
        if ($cita->estado === 'completada') {
            return back()->with(
                'error',
                'No se puede cancelar una cita que ya fue completada.'
            );
        }

        $cita->update([
            'estado' => 'cancelada',
        ]);

        return back()->with(
            'success',
            'Cita cancelada correctamente.'
        );
    }


    /**
     * Volver una cita cancelada o completada a pendiente.
     */
    public function reactivar(Cita $cita)
    {
        $cita->update([
            'estado' => 'pendiente',
        ]);

        return back()->with(
            'success',
            'Cita reactivada correctamente.'
        );
    }
}
