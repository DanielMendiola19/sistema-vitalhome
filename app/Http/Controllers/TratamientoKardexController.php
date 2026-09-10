<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\Tratamiento;
use App\Models\Medicamento;
use Illuminate\Http\Request;

class TratamientoKardexController extends Controller
{
    /**
     * Mostrar la lista general de pacientes
     * para acceder a su Tratamiento / Kardex.
     */
    public function indexGeneral(Request $request)
    {
        $buscar = $request->input('buscar');

        $pacientes = Paciente::with([
            'tratamientos'
        ])
        ->when($buscar, function ($query) use ($buscar) {
            $query->where(function ($q) use ($buscar) {
                $q->where(
                    'nombre',
                    'ILIKE',
                    '%' . $buscar . '%'
                )
                ->orWhere(
                    'apellido',
                    'ILIKE',
                    '%' . $buscar . '%'
                )
                ->orWhere(
                    'ci',
                    'ILIKE',
                    '%' . $buscar . '%'
                );
            });
        })
        ->orderBy('apellido')
        ->orderBy('nombre')
        ->get();

        return view(
            'tratamientos_kardex.index',
            compact(
                'pacientes',
                'buscar'
            )
        );
    }

    /**
     * Mostrar tratamientos / Kardex de un paciente.
     */
    public function index($pacienteId)
    {
        $paciente = Paciente::with([
            'tratamientos' => function ($query) {
                $query
                    ->with('medicamento')
                    ->orderByDesc('fecha_inicio')
                    ->orderByDesc('id');
            }
        ])->findOrFail($pacienteId);

        /*
         * La tabla medicamentos utiliza la columna
         * "activo" en lugar de "estado".
         */
        $medicamentos = Medicamento::query()
            ->where('activo', true)
            ->orderBy('nombre')
            ->get();

        return view(
            'tratamientos_kardex.show',
            compact(
                'paciente',
                'medicamentos'
            )
        );
    }

    /**
     * Actualizar el diagnóstico del paciente.
     */
    public function actualizarDiagnostico(
        Request $request,
        $pacienteId
    ) {
        $paciente = Paciente::findOrFail($pacienteId);

        $validated = $request->validate([
            'diagnostico' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ], [
            'diagnostico.max' =>
                'El diagnóstico no puede superar los 1000 caracteres.',
        ]);

        $paciente->update([
            'diagnostico' => $validated['diagnostico'] ?? null,
        ]);

        return redirect()
            ->route(
                'tratamientos_kardex.index',
                [
                    'paciente' => $paciente->id
                ]
            )
            ->with(
                'success',
                'Diagnóstico actualizado correctamente.'
            );
    }

    /**
     * Registrar nuevo tratamiento.
     */
    public function store(Request $request, $pacienteId)
    {
        $paciente = Paciente::findOrFail($pacienteId);

        $validated = $request->validate([
            'medicamento_id' => [
                'required',
                'exists:medicamentos,id',
            ],

            'dosis' => [
                'required',
                'string',
                'max:100',
            ],

            'frecuencia' => [
                'required',
                'string',
                'max:100',
            ],

            'via_administracion' => [
                'nullable',
                'string',
                'max:100',
            ],

            'fecha_inicio' => [
                'required',
                'date',
            ],

            'fecha_fin' => [
                'nullable',
                'date',
                'after_or_equal:fecha_inicio',
            ],

            'hora_tm' => [
                'nullable',
                'date_format:H:i',
            ],

            'hora_tt' => [
                'nullable',
                'date_format:H:i',
            ],

            'hora_tn' => [
                'nullable',
                'date_format:H:i',
            ],

            'indicaciones' => [
                'nullable',
                'string',
            ],

            'estado' => [
                'required',
                'in:activo,suspendido,finalizado',
            ],
        ], [
            'medicamento_id.required' =>
                'Debes seleccionar un medicamento.',

            'medicamento_id.exists' =>
                'El medicamento seleccionado no es válido.',

            'dosis.required' =>
                'La dosis es obligatoria.',

            'frecuencia.required' =>
                'La frecuencia es obligatoria.',

            'fecha_inicio.required' =>
                'La fecha de inicio es obligatoria.',

            'fecha_fin.after_or_equal' =>
                'La fecha de finalización no puede ser anterior a la fecha de inicio.',

            'hora_tm.date_format' =>
                'La hora de mañana no es válida.',

            'hora_tt.date_format' =>
                'La hora de tarde no es válida.',

            'hora_tn.date_format' =>
                'La hora de noche no es válida.',
        ]);

        $paciente->tratamientos()->create($validated);

        return redirect()
            ->route(
                'tratamientos_kardex.index',
                [
                    'paciente' => $paciente->id
                ]
            )
            ->with(
                'success',
                'Tratamiento registrado correctamente.'
            );
    }

    /**
     * Actualizar tratamiento.
     */
    public function update(
        Request $request,
        $pacienteId,
        $tratamientoId
    ) {
        $paciente = Paciente::findOrFail($pacienteId);

        $tratamiento = $paciente
            ->tratamientos()
            ->findOrFail($tratamientoId);

        $validated = $request->validate([
            'medicamento_id' => [
                'required',
                'exists:medicamentos,id',
            ],

            'dosis' => [
                'required',
                'string',
                'max:100',
            ],

            'frecuencia' => [
                'required',
                'string',
                'max:100',
            ],

            'via_administracion' => [
                'nullable',
                'string',
                'max:100',
            ],

            'fecha_inicio' => [
                'required',
                'date',
            ],

            'fecha_fin' => [
                'nullable',
                'date',
                'after_or_equal:fecha_inicio',
            ],

            'hora_tm' => [
                'nullable',
                'date_format:H:i',
            ],

            'hora_tt' => [
                'nullable',
                'date_format:H:i',
            ],

            'hora_tn' => [
                'nullable',
                'date_format:H:i',
            ],

            'indicaciones' => [
                'nullable',
                'string',
            ],

            'estado' => [
                'required',
                'in:activo,suspendido,finalizado',
            ],
        ], [
            'medicamento_id.required' =>
                'Debes seleccionar un medicamento.',

            'medicamento_id.exists' =>
                'El medicamento seleccionado no es válido.',

            'dosis.required' =>
                'La dosis es obligatoria.',

            'frecuencia.required' =>
                'La frecuencia es obligatoria.',

            'fecha_inicio.required' =>
                'La fecha de inicio es obligatoria.',

            'fecha_fin.after_or_equal' =>
                'La fecha de finalización no puede ser anterior a la fecha de inicio.',

            'hora_tm.date_format' =>
                'La hora de mañana no es válida.',

            'hora_tt.date_format' =>
                'La hora de tarde no es válida.',

            'hora_tn.date_format' =>
                'La hora de noche no es válida.',
        ]);

        $tratamiento->update($validated);

        return redirect()
            ->route(
                'tratamientos_kardex.index',
                [
                    'paciente' => $paciente->id
                ]
            )
            ->with(
                'success',
                'Tratamiento actualizado correctamente.'
            );
    }

    /**
     * Finalizar tratamiento.
     */
    public function finalizar(
        $pacienteId,
        $tratamientoId
    ) {
        $paciente = Paciente::findOrFail($pacienteId);

        $tratamiento = $paciente
            ->tratamientos()
            ->findOrFail($tratamientoId);

        $tratamiento->update([
            'estado' => 'finalizado',
            'fecha_fin' => now()->toDateString(),
        ]);

        return redirect()
            ->route(
                'tratamientos_kardex.index',
                [
                    'paciente' => $paciente->id
                ]
            )
            ->with(
                'success',
                'Tratamiento finalizado correctamente.'
            );
    }
}
