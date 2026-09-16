<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class PacienteController extends Controller
{
    /**
     * Listado de pacientes.
     */
    public function index(Request $request)
    {
        $buscar = trim($request->query('buscar', ''));
        $estado = $request->query('estado', 'Todos');

        $query = Paciente::query()
            ->with([
                'tratamientos' => function ($query) {
                    $query
                        ->where('estado', 'activo')
                        ->with('medicamento');
                }
            ])
            ->orderBy('apellido')
            ->orderBy('nombre');

        if ($buscar !== '') {
            $query->where(function ($query) use ($buscar) {
                $query->where('nombre', 'ILIKE', "%{$buscar}%")
                    ->orWhere('apellido', 'ILIKE', "%{$buscar}%")
                    ->orWhere('ci', 'ILIKE', "%{$buscar}%")
                    ->orWhere('observaciones', 'ILIKE', "%{$buscar}%")
                    ->orWhere('seguro', 'ILIKE', "%{$buscar}%")
                    ->orWhere('especialidades', 'ILIKE', "%{$buscar}%")
                    ->orWhere('medicamentos_ingreso', 'ILIKE', "%{$buscar}%");
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Estado real del paciente
        |--------------------------------------------------------------------------
        | Activo   = actualmente reside en VITALHOME.
        | Inactivo = ya no reside en VITALHOME o falleció.
        */
        if ($estado === 'Activos') {
            $query->where('estado', 'activo');
        } elseif ($estado === 'Inactivos') {
            $query->where('estado', 'inactivo');
        }

        $pacientes = $query->get();

        return view('pacientes.index', compact(
            'pacientes',
            'buscar',
            'estado'
        ));
    }

    /**
     * Guardar nuevo paciente.
     */
    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'nombre' => ['required', 'string', 'max:100'],
                'apellido' => ['required', 'string', 'max:100'],
                'ci' => ['required', 'string', 'max:20', 'unique:pacientes,ci'],
                'fecha_nacimiento' => ['required', 'date'],
                'sexo' => ['required', 'string', 'max:20'],
                'telefono' => ['nullable', 'string', 'max:20'],
                'direccion' => ['nullable', 'string', 'max:200'],
                'observaciones' => ['nullable', 'string'],
                'tiene_seguro' => ['required', 'boolean'],
                'seguro' => [
                    Rule::requiredIf(fn () => $request->boolean('tiene_seguro')),
                    'nullable',
                    'string',
                    'max:150',
                ],
                'especialidades' => ['nullable', 'string', 'max:1000'],
                'medicamentos_ingreso' => ['nullable', 'string', 'max:5000'],
                'estado' => ['required', Rule::in(['activo', 'inactivo'])],
                'motivo_inactividad' => [
                    Rule::requiredIf(fn () => $request->input('estado') === 'inactivo'),
                    'nullable',
                    Rule::in(['retiro', 'fallecimiento']),
                ],
            ],
            [
                'nombre.required' => 'El nombre es obligatorio.',
                'nombre.max' => 'El nombre no puede superar los 100 caracteres.',
                'apellido.required' => 'El apellido es obligatorio.',
                'apellido.max' => 'El apellido no puede superar los 100 caracteres.',
                'ci.required' => 'El carnet de identidad es obligatorio.',
                'ci.unique' => 'El carnet de identidad ya está registrado.',
                'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
                'fecha_nacimiento.date' => 'La fecha de nacimiento no es válida.',
                'sexo.required' => 'El sexo es obligatorio.',
                'telefono.max' => 'El teléfono no puede superar los 20 caracteres.',
                'direccion.max' => 'La dirección no puede superar los 200 caracteres.',
                'tiene_seguro.required' => 'Debes indicar si el paciente cuenta con seguro.',
                'seguro.required' => 'Debes especificar el seguro del paciente.',
                'estado.required' => 'El estado del paciente es obligatorio.',
                'motivo_inactividad.required' => 'Debes indicar el motivo de inactividad.',
            ]
        );

        $validated['tiene_seguro'] = $request->boolean('tiene_seguro');

        if (!$validated['tiene_seguro']) {
            $validated['seguro'] = null;
        }

        if ($validated['estado'] === 'activo') {
            $validated['motivo_inactividad'] = null;
        }

        Paciente::create($validated);

        return redirect()
            ->route('pacientes.index')
            ->with('success', 'Paciente registrado correctamente.');
    }

    /**
     * Mostrar detalle del paciente.
     */
    public function show($id)
    {
        $paciente = Paciente::with([
            'tratamientos' => function ($query) {
                $query
                    ->with('medicamento')
                    ->orderByDesc('fecha_inicio');
            },
            'observaciones' => function ($query) {
                $query
                    ->with('usuario')
                    ->latest();
            },
            'signosVitales' => function ($query) {
                $query->orderByDesc('fecha_registro');
            }
        ])->findOrFail($id);

        return view('pacientes.show', compact('paciente'));
    }

    /**
     * Actualizar paciente.
     */
    public function update(Request $request, $id)
    {
        $paciente = Paciente::findOrFail($id);

        $validated = $request->validate(
            [
                'nombre' => ['required', 'string', 'max:100'],
                'apellido' => ['required', 'string', 'max:100'],
                'ci' => [
                    'required',
                    'string',
                    'max:20',
                    Rule::unique('pacientes', 'ci')->ignore($paciente->id),
                ],
                'fecha_nacimiento' => ['required', 'date'],
                'sexo' => ['required', 'string', 'max:20'],
                'telefono' => ['nullable', 'string', 'max:20'],
                'direccion' => ['nullable', 'string', 'max:200'],
                'observaciones' => ['nullable', 'string'],
                'tiene_seguro' => ['required', 'boolean'],
                'seguro' => [
                    Rule::requiredIf(fn () => $request->boolean('tiene_seguro')),
                    'nullable',
                    'string',
                    'max:150',
                ],
                'especialidades' => ['nullable', 'string', 'max:1000'],
                'medicamentos_ingreso' => ['nullable', 'string', 'max:5000'],
                'estado' => ['required', Rule::in(['activo', 'inactivo'])],
                'motivo_inactividad' => [
                    Rule::requiredIf(fn () => $request->input('estado') === 'inactivo'),
                    'nullable',
                    Rule::in(['retiro', 'fallecimiento']),
                ],
            ],
            [
                'nombre.required' => 'El nombre es obligatorio.',
                'apellido.required' => 'El apellido es obligatorio.',
                'ci.required' => 'El carnet de identidad es obligatorio.',
                'ci.unique' => 'El carnet de identidad ya está registrado.',
                'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
                'sexo.required' => 'El sexo es obligatorio.',
                'tiene_seguro.required' => 'Debes indicar si el paciente cuenta con seguro.',
                'seguro.required' => 'Debes especificar el seguro del paciente.',
                'estado.required' => 'El estado del paciente es obligatorio.',
                'motivo_inactividad.required' => 'Debes indicar el motivo de inactividad.',
            ]
        );

        $validated['tiene_seguro'] = $request->boolean('tiene_seguro');

        if (!$validated['tiene_seguro']) {
            $validated['seguro'] = null;
        }

        if ($validated['estado'] === 'activo') {
            $validated['motivo_inactividad'] = null;
        }

        $paciente->update($validated);

        return redirect()
            ->route('pacientes.show', $paciente->id)
            ->with('success', 'Información del paciente actualizada correctamente.');
    }

    /**
     * Registrar una nueva observación.
     */
    public function storeObservation(Request $request, int $id)
    {
        $paciente = Paciente::findOrFail($id);

        $validated = $request->validate([
            'observacion' => ['required', 'string', 'max:5000'],
        ], [
            'observacion.required' => 'La observación es obligatoria.',
            'observacion.max' => 'La observación no puede superar los 5000 caracteres.',
        ]);

        $paciente->observacionesClinicas()->create([
            'usuario_id' => Auth::id(),
            'observacion' => $validated['observacion'],
        ]);

        return redirect()
            ->route('pacientes.show', ['id' => $paciente->getKey()])
            ->with('success', 'Observación registrada correctamente.');
    }

    /**
     * Registrar signos vitales.
     */
    public function storeSignosVitales(Request $request, $id)
    {
        $paciente = Paciente::findOrFail($id);

        $validated = $request->validate([
            'fecha_registro' => ['required', 'date'],
            'presion_arterial' => [
                'required', 'string', 'max:20', 'regex:/^\d{2,3}\/\d{2,3}$/',
            ],
            'frecuencia_cardiaca' => ['required', 'integer', 'min:1', 'max:300'],
            'frecuencia_respiratoria' => ['required', 'integer', 'min:1', 'max:100'],
            'temperatura' => ['required', 'numeric', 'min:25', 'max:45'],
            'spo2' => ['required', 'integer', 'min:0', 'max:100'],
        ], [
            'fecha_registro.required' => 'La fecha y hora son obligatorias.',
            'fecha_registro.date' => 'La fecha y hora no son válidas.',
            'presion_arterial.required' => 'La presión arterial es obligatoria.',
            'presion_arterial.regex' => 'La presión arterial debe tener el formato 130/80.',
            'frecuencia_cardiaca.required' => 'La frecuencia cardíaca es obligatoria.',
            'frecuencia_cardiaca.integer' => 'La frecuencia cardíaca debe ser un número.',
            'frecuencia_cardiaca.min' => 'La frecuencia cardíaca no es válida.',
            'frecuencia_cardiaca.max' => 'La frecuencia cardíaca no es válida.',
            'frecuencia_respiratoria.required' => 'La frecuencia respiratoria es obligatoria.',
            'frecuencia_respiratoria.integer' => 'La frecuencia respiratoria debe ser un número.',
            'frecuencia_respiratoria.min' => 'La frecuencia respiratoria no es válida.',
            'frecuencia_respiratoria.max' => 'La frecuencia respiratoria no es válida.',
            'temperatura.required' => 'La temperatura es obligatoria.',
            'temperatura.numeric' => 'La temperatura debe ser un número.',
            'temperatura.min' => 'La temperatura no es válida.',
            'temperatura.max' => 'La temperatura no es válida.',
            'spo2.required' => 'La saturación de oxígeno es obligatoria.',
            'spo2.integer' => 'La saturación de oxígeno debe ser un número.',
            'spo2.min' => 'La saturación de oxígeno no puede ser menor a 0%.',
            'spo2.max' => 'La saturación de oxígeno no puede superar 100%.',
        ]);

        $paciente->signosVitales()->create($validated);

        return redirect()
            ->route('pacientes.show', $paciente->id)
            ->with('success', 'Signos vitales registrados correctamente.');
    }
}
