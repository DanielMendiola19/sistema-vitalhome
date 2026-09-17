<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PacienteController extends Controller
{
    /**
     * Listado de pacientes.
     */
    public function index(Request $request)
    {
        $buscar = trim($request->query('buscar', ''));
        $estado = $request->query('estado', 'Todos');

        /*
        |--------------------------------------------------------------------------
        | Listado optimizado
        |--------------------------------------------------------------------------
        */

        $tratamientosActivos = DB::table('tratamientos as t')
            ->leftJoin('medicamentos as m', 'm.id', '=', 't.medicamento_id')
            ->where('t.estado', 'activo')
            ->groupBy('t.paciente_id')
            ->selectRaw("
                t.paciente_id,
                COUNT(*) AS tratamientos_activos_count,
                STRING_AGG(
                    COALESCE(m.nombre, 'Medicamento no disponible'),
                    '||' ORDER BY m.nombre
                ) AS medicamentos_activos
            ");

        $query = Paciente::query()
            ->leftJoinSub($tratamientosActivos, 'ta', function ($join) {
                $join->on('ta.paciente_id', '=', 'pacientes.id');
            })
            ->select([
                'pacientes.*',
                DB::raw('COALESCE(ta.tratamientos_activos_count, 0) AS tratamientos_activos_count'),
                'ta.medicamentos_activos',
            ])
            ->orderBy('pacientes.apellido')
            ->orderBy('pacientes.nombre');

        if ($buscar !== '') {
            $query->where(function ($query) use ($buscar) {
                $query->where('pacientes.nombre', 'ILIKE', "%{$buscar}%")
                    ->orWhere('pacientes.apellido', 'ILIKE', "%{$buscar}%")
                    ->orWhere('pacientes.ci', 'ILIKE', "%{$buscar}%")
                    ->orWhere('pacientes.observaciones', 'ILIKE', "%{$buscar}%")
                    ->orWhere('pacientes.seguro', 'ILIKE', "%{$buscar}%")
                    ->orWhere('pacientes.especialidades', 'ILIKE', "%{$buscar}%")
                    ->orWhere('pacientes.medicamentos_ingreso', 'ILIKE', "%{$buscar}%");
            });
        }

        if ($estado === 'Activos') {
            $query->where('pacientes.estado', 'activo');
        } elseif ($estado === 'Inactivos') {
            $query->where('pacientes.estado', 'inactivo');
        }

        $pacientes = $query->get();

        /*
        |--------------------------------------------------------------------------
        | Compatibilidad con la vista actual
        |--------------------------------------------------------------------------
        */

        foreach ($pacientes as $paciente) {
            $nombresMedicamentos = $paciente->medicamentos_activos
                ? explode('||', $paciente->medicamentos_activos)
                : [];

            $tratamientos = collect($nombresMedicamentos)->map(function ($nombre) {
                $tratamiento = new \App\Models\Tratamiento();
                $medicamento = new \App\Models\Medicamento();

                $medicamento->nombre = $nombre;
                $tratamiento->setRelation('medicamento', $medicamento);

                return $tratamiento;
            });

            $paciente->setRelation('tratamientos', $tratamientos);
        }

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
        /*
        |--------------------------------------------------------------------------
        | NORMALIZAR CI
        |--------------------------------------------------------------------------
        |
        | Algunos residentes no cuentan con carnet de identidad.
        |
        | Si el CI:
        | - está vacío
        | - contiene solamente ceros, sin importar la cantidad
        |
        | se guarda como NULL.
        |
        | Los CI reales continúan siendo únicos.
        |
        */

        $ci = trim((string) $request->input('ci', ''));

        if ($ci === '' || preg_match('/^0+$/', $ci)) {
            $request->merge([
                'ci' => null,
            ]);
        } else {
            $request->merge([
                'ci' => $ci,
            ]);
        }

        $validated = $request->validate(
            [
                'nombre' => ['required', 'string', 'max:100'],
                'apellido' => ['required', 'string', 'max:100'],

                // Puede ser NULL para pacientes que no tienen CI.
                // Si existe un CI real, debe seguir siendo único.
                'ci' => [
                    'nullable',
                    'string',
                    'max:20',
                    'unique:pacientes,ci',
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

                'especialidades' => [
                    'nullable',
                    'string',
                    'max:1000',
                ],

                'medicamentos_ingreso' => [
                    'nullable',
                    'string',
                    'max:5000',
                ],

                'estado' => [
                    'required',
                    Rule::in(['activo', 'inactivo']),
                ],

                'motivo_inactividad' => [
                    Rule::requiredIf(
                        fn () => $request->input('estado') === 'inactivo'
                    ),
                    'nullable',
                    Rule::in(['retiro', 'fallecimiento']),
                ],
            ],
            [
                'nombre.required' => 'El nombre es obligatorio.',
                'nombre.max' => 'El nombre no puede superar los 100 caracteres.',

                'apellido.required' => 'El apellido es obligatorio.',
                'apellido.max' => 'El apellido no puede superar los 100 caracteres.',

                'ci.unique' => 'El carnet de identidad ya está registrado.',
                'ci.max' => 'El carnet de identidad no puede superar los 20 caracteres.',

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
        /*
        |--------------------------------------------------------------------------
        | Perfil optimizado
        |--------------------------------------------------------------------------
        */

        $fila = DB::table('pacientes as p')
            ->where('p.id', $id)
            ->select([
                'p.*',

                DB::raw("
                    COALESCE(
                        (
                            SELECT json_agg(
                                json_build_object(
                                    'id', t.id,
                                    'medicamento_id', t.medicamento_id,
                                    'medicamento_nombre', m.nombre,
                                    'frecuencia', t.frecuencia,
                                    'via_administracion', t.via_administracion,
                                    'fecha_inicio', t.fecha_inicio,
                                    'dosis', t.dosis,
                                    'estado', t.estado
                                )
                                ORDER BY t.fecha_inicio DESC
                            )
                            FROM tratamientos t
                            LEFT JOIN medicamentos m
                                ON m.id = t.medicamento_id
                            WHERE t.paciente_id = p.id
                        ),
                        '[]'::json
                    ) AS tratamientos_json
                "),

                DB::raw("
                    COALESCE(
                        (
                            SELECT json_agg(
                                json_build_object(
                                    'id', po.id,
                                    'observacion', po.observacion,
                                    'created_at', po.created_at,
                                    'updated_at', po.updated_at,
                                    'usuario_id', po.usuario_id,
                                    'usuario_nombre', u.nombre,
                                    'usuario_apellido', u.apellido,
                                    'usuario_rol', u.rol
                                )
                                ORDER BY po.created_at DESC
                            )
                            FROM patient_observations po
                            LEFT JOIN users u
                                ON u.id = po.usuario_id
                            WHERE po.paciente_id = p.id
                        ),
                        '[]'::json
                    ) AS observaciones_clinicas_json
                "),

                DB::raw("
                    COALESCE(
                        (
                            SELECT json_agg(
                                json_build_object(
                                    'id', sv.id,
                                    'fecha_registro', sv.fecha_registro,
                                    'presion_arterial', sv.presion_arterial,
                                    'frecuencia_cardiaca', sv.frecuencia_cardiaca,
                                    'frecuencia_respiratoria', sv.frecuencia_respiratoria,
                                    'temperatura', sv.temperatura,
                                    'spo2', sv.spo2
                                )
                                ORDER BY sv.fecha_registro DESC
                            )
                            FROM signos_vitales sv
                            WHERE sv.paciente_id = p.id
                        ),
                        '[]'::json
                    ) AS signos_vitales_json
                "),
            ])
            ->first();

        abort_if(!$fila, 404);

        $paciente = (new Paciente())->newFromBuilder((array) $fila);

        /*
        |--------------------------------------------------------------------------
        | Hidratar relaciones sin nuevas consultas
        |--------------------------------------------------------------------------
        */

        $tratamientosData = json_decode(
            $fila->tratamientos_json ?? '[]',
            true
        ) ?: [];

        $tratamientos = collect($tratamientosData)->map(function ($data) {
            $tratamiento = new \App\Models\Tratamiento();

            $tratamiento->forceFill([
                'id' => $data['id'] ?? null,
                'medicamento_id' => $data['medicamento_id'] ?? null,
                'frecuencia' => $data['frecuencia'] ?? null,
                'via_administracion' => $data['via_administracion'] ?? null,
                'fecha_inicio' => $data['fecha_inicio'] ?? null,
                'dosis' => $data['dosis'] ?? null,
                'estado' => $data['estado'] ?? null,
            ]);

            $medicamento = new \App\Models\Medicamento();

            $medicamento->forceFill([
                'id' => $data['medicamento_id'] ?? null,
                'nombre' => $data['medicamento_nombre'] ?? null,
            ]);

            $tratamiento->setRelation('medicamento', $medicamento);

            return $tratamiento;
        });

        $observacionesData = json_decode(
            $fila->observaciones_clinicas_json ?? '[]',
            true
        ) ?: [];

        $observacionesClinicas = collect($observacionesData)->map(function ($data) {
            $observacion = new \App\Models\PatientObservation();

            $observacion->forceFill([
                'id' => $data['id'] ?? null,
                'observacion' => $data['observacion'] ?? null,
                'usuario_id' => $data['usuario_id'] ?? null,
                'created_at' => $data['created_at'] ?? null,
                'updated_at' => $data['updated_at'] ?? null,
            ]);

            if (!empty($data['usuario_id'])) {
                $usuario = new \App\Models\User();

                $usuario->forceFill([
                    'id' => $data['usuario_id'],
                    'nombre' => $data['usuario_nombre'] ?? null,
                    'apellido' => $data['usuario_apellido'] ?? null,
                    'rol' => $data['usuario_rol'] ?? null,
                ]);

                $observacion->setRelation('usuario', $usuario);
            } else {
                $observacion->setRelation('usuario', null);
            }

            return $observacion;
        });

        $signosData = json_decode(
            $fila->signos_vitales_json ?? '[]',
            true
        ) ?: [];

        $signosVitales = collect($signosData)->map(function ($data) {
            $signo = new \App\Models\SignoVital();

            $signo->forceFill([
                'id' => $data['id'] ?? null,
                'fecha_registro' => $data['fecha_registro'] ?? null,
                'presion_arterial' => $data['presion_arterial'] ?? null,
                'frecuencia_cardiaca' => $data['frecuencia_cardiaca'] ?? null,
                'frecuencia_respiratoria' => $data['frecuencia_respiratoria'] ?? null,
                'temperatura' => $data['temperatura'] ?? null,
                'spo2' => $data['spo2'] ?? null,
            ]);

            return $signo;
        });

        $paciente->setRelation('tratamientos', $tratamientos);
        $paciente->setRelation('observacionesClinicas', $observacionesClinicas);
        $paciente->setRelation('observaciones', $observacionesClinicas);
        $paciente->setRelation('signosVitales', $signosVitales);

        return view('pacientes.show', compact('paciente'));
    }

    /**
     * Actualizar paciente.
     */
    public function update(Request $request, $id)
    {
        $paciente = Paciente::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | NORMALIZAR CI
        |--------------------------------------------------------------------------
        */

        $ci = trim((string) $request->input('ci', ''));

        if ($ci === '' || preg_match('/^0+$/', $ci)) {
            $request->merge([
                'ci' => null,
            ]);
        } else {
            $request->merge([
                'ci' => $ci,
            ]);
        }

        $validated = $request->validate(
            [
                'nombre' => ['required', 'string', 'max:100'],
                'apellido' => ['required', 'string', 'max:100'],

                'ci' => [
                    'nullable',
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

                'estado' => [
                    'required',
                    Rule::in(['activo', 'inactivo']),
                ],

                'motivo_inactividad' => [
                    Rule::requiredIf(
                        fn () => $request->input('estado') === 'inactivo'
                    ),
                    'nullable',
                    Rule::in(['retiro', 'fallecimiento']),
                ],
            ],
            [
                'nombre.required' => 'El nombre es obligatorio.',
                'apellido.required' => 'El apellido es obligatorio.',

                'ci.unique' => 'El carnet de identidad ya está registrado.',
                'ci.max' => 'El carnet de identidad no puede superar los 20 caracteres.',

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
        $validated = $request->validate([
            'observacion' => ['required', 'string', 'max:5000'],
        ], [
            'observacion.required' => 'La observación es obligatoria.',
            'observacion.max' => 'La observación no puede superar los 5000 caracteres.',
        ]);

        DB::table('patient_observations')->insert([
            'paciente_id' => $id,
            'usuario_id' => Auth::id(),
            'observacion' => $validated['observacion'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('pacientes.show', ['id' => $id])
            ->with('success', 'Observación registrada correctamente.');
    }

    /**
     * Registrar signos vitales.
     */
    public function storeSignosVitales(Request $request, $id)
    {
        $validated = $request->validate([
            'fecha_registro' => ['required', 'date'],

            'presion_arterial' => [
                'required',
                'string',
                'max:20',
                'regex:/^\d{2,3}\/\d{2,3}$/',
            ],

            'frecuencia_cardiaca' => [
                'required',
                'integer',
                'min:1',
                'max:300',
            ],

            'frecuencia_respiratoria' => [
                'required',
                'integer',
                'min:1',
                'max:100',
            ],

            'temperatura' => [
                'required',
                'numeric',
                'min:25',
                'max:45',
            ],

            'spo2' => [
                'required',
                'integer',
                'min:0',
                'max:100',
            ],
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

        DB::table('signos_vitales')->insert([
            'paciente_id' => $id,
            'fecha_registro' => $validated['fecha_registro'],
            'presion_arterial' => $validated['presion_arterial'],
            'frecuencia_cardiaca' => $validated['frecuencia_cardiaca'],
            'frecuencia_respiratoria' => $validated['frecuencia_respiratoria'],
            'temperatura' => $validated['temperatura'],
            'spo2' => $validated['spo2'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('pacientes.show', $id)
            ->with('success', 'Signos vitales registrados correctamente.');
    }
}
