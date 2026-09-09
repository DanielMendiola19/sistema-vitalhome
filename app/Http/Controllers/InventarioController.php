<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegistrarEntradaInventarioRequest;
use App\Http\Requests\RegistrarSalidaInventarioRequest;
use App\Http\Requests\TransferirInventarioRequest;
use App\Http\Requests\RegistrarInventarioPacienteRequest;
use App\Models\Inventario;
use App\Models\InventarioPaciente;
use App\Models\Medicamento;
use App\Models\Paciente;
use App\Services\InventarioService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class InventarioController extends Controller
{
    public function __construct(
        protected InventarioService $inventarioService
    ) {
    }

    /**
     * Inventario general.
     */
    public function index(Request $request): View
    {
        $buscar = trim(
            (string) $request->get('buscar', '')
        );

        $filtro = $request->get(
            'filtro',
            'todos'
        );

        $inventarios = Inventario::query()
            ->with([
                'medicamento',
            ])
            ->when(
                $buscar !== '',
                function ($query) use ($buscar) {
                    $query->whereHas(
                        'medicamento',
                        function ($medicamentoQuery) use ($buscar) {
                            $medicamentoQuery
                                ->where(
                                    'nombre',
                                    'like',
                                    "%{$buscar}%"
                                )
                                ->orWhere(
                                    'principio_activo',
                                    'like',
                                    "%{$buscar}%"
                                )
                                ->orWhere(
                                    'presentacion',
                                    'like',
                                    "%{$buscar}%"
                                );
                        }
                    );
                }
            )
            ->when(
                $filtro === 'disponibles',
                function ($query) {
                    $query->whereColumn(
                        'cantidad_actual',
                        '>',
                        'cantidad_minima'
                    );
                }
            )
            ->when(
                $filtro === 'stock_bajo',
                function ($query) {
                    $query->whereColumn(
                        'cantidad_actual',
                        '<=',
                        'cantidad_minima'
                    )
                    ->where(
                        'cantidad_actual',
                        '>',
                        0
                    );
                }
            )
            ->when(
                $filtro === 'agotados',
                function ($query) {
                    $query->where(
                        'cantidad_actual',
                        '<=',
                        0
                    );
                }
            )
            ->when(
                $filtro === 'vencidos',
                function ($query) {
                    $query->whereNotNull(
                        'fecha_vencimiento'
                    )
                    ->whereDate(
                        'fecha_vencimiento',
                        '<',
                        today()
                    );
                }
            )
            ->orderBy(
                'fecha_vencimiento'
            )
            ->orderBy(
                'id'
            )
            ->paginate(15)
            ->withQueryString();

        $medicamentos = Medicamento::query()
            ->where('activo', true)
            ->orderBy('nombre')
            ->get();

        $pacientes = Paciente::query()
            ->orderBy('nombre')
            ->orderBy('apellido')
            ->get();

        $detalle = null;

        if ($request->filled('medicamento')) {
            $detalle = Inventario::query()
                ->with([
                    'medicamento',
                    'movimientos' => function ($query) {
                        $query
                            ->latest('fecha')
                            ->limit(10);
                    },
                ])
                ->find(
                    $request->integer('medicamento')
                );
        }

        return view(
            'inventario.index',
            compact(
                'inventarios',
                'medicamentos',
                'pacientes',
                'detalle',
                'buscar',
                'filtro'
            )
        );
    }

    /**
     * Vista de selección de pacientes.
     */
    public function pacientes(Request $request): View
    {
        $buscar = trim(
            (string) $request->get('buscar', '')
        );

        $pacientes = Paciente::query()
            ->with([
                'tratamientos.medicamento',
                'inventariosPacientes.medicamento',
            ])
            ->when(
                $buscar !== '',
                function ($query) use ($buscar) {
                    $query->where(function ($q) use ($buscar) {
                        $q->where(
                            'nombre',
                            'like',
                            "%{$buscar}%"
                        )
                        ->orWhere(
                            'apellido',
                            'like',
                            "%{$buscar}%"
                        )
                        ->orWhere(
                            'ci',
                            'like',
                            "%{$buscar}%"
                        );
                    });
                }
            )
            ->orderBy('nombre')
            ->orderBy('apellido')
            ->paginate(12)
            ->withQueryString();

        return view(
            'inventario.pacientes',
            compact(
                'pacientes',
                'buscar'
            )
        );
    }

    /**
     * Medicamentos de un paciente.
     */
    public function paciente(
        Paciente $paciente
    ): View {
        $paciente->load([
            'inventariosPacientes' => function ($query) {
                $query
                    ->with('medicamento')
                    ->orderBy('id');
            },

            'movimientosInventario' => function ($query) {
                $query
                    ->whereNotNull('inventario_paciente_id')
                    ->with([
                        'inventarioPaciente.medicamento',
                    ])
                    ->latest('fecha');
            },

            'tratamientos.medicamento',
        ]);

        $medicamentos = Medicamento::query()
            ->where('activo', true)
            ->orderBy('nombre')
            ->get();

        $detalle = null;

        return view(
            'inventario.paciente',
            compact(
                'paciente',
                'medicamentos',
                'detalle'
            )
        );
    }


    public function registrarInventarioPaciente(
        RegistrarInventarioPacienteRequest $request
    ): RedirectResponse {
        try {
            $paciente = Paciente::findOrFail(
                $request->integer('paciente_id')
            );

            $medicamento = Medicamento::query()
                ->whereKey($request->integer('medicamento_id'))
                ->where('activo', true)
                ->firstOrFail();

            $this->inventarioService->registrarInventarioPaciente(
                paciente: $paciente,
                medicamento: $medicamento,
                cantidad: $request->integer('cantidad'),
                cantidadMinima: $request->integer('cantidad_minima', 0),
                fechaVencimiento: $request->input('fecha_vencimiento'),
                lote: $request->input('lote')
            );

            return back()->with(
                'success',
                'Medicamento registrado correctamente en el inventario del paciente.'
            );
        } catch (Throwable $e) {
            report($e);

            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Detalle de un medicamento del inventario general.
     */
    public function detalle(
        Inventario $inventario
    ): View {
        $inventario->load([
            'medicamento',
            'movimientos' => function ($query) {
                $query
                    ->with('paciente')
                    ->latest('fecha');
            },
        ]);

        $pacientes = Paciente::query()
            ->orderBy('nombre')
            ->orderBy('apellido')
            ->get();

        return view(
            'inventario.detalle',
            compact(
                'inventario',
                'pacientes'
            )
        );
    }

    public function actualizar(
        Request $request,
        Inventario $inventario
    ): RedirectResponse {
        $datos = $request->validate([
            'cantidad_minima' => [
                'required',
                'integer',
                'min:0',
            ],

            'cantidad_maxima' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'fecha_vencimiento' => [
                'nullable',
                'date',
            ],

            'lote' => [
                'nullable',
                'string',
                'max:100',
            ],
        ]);

        if (
            $datos['cantidad_maxima'] !== null
            && $datos['cantidad_maxima'] < $datos['cantidad_minima']
        ) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'El stock máximo no puede ser menor que el stock mínimo.'
                );
        }

        $inventario->cantidad_minima =
            $datos['cantidad_minima'];

        $inventario->cantidad_maxima =
            $datos['cantidad_maxima'];

        $inventario->fecha_vencimiento =
            $datos['fecha_vencimiento'];

        $inventario->lote =
            $datos['lote'];

        $inventario->estado =
            $this->inventarioService->determinarEstado(
                $inventario->cantidad_actual,
                $inventario->cantidad_minima
            );

        $inventario->save();

        return back()->with(
            'success',
            'Configuración del inventario actualizada correctamente.'
        );
    }

    /**
     * Registrar entrada.
     */
    public function entrada(
        RegistrarEntradaInventarioRequest $request
    ): RedirectResponse {
        try {
            $medicamento = Medicamento::query()
                ->whereKey($request->integer('medicamento_id'))
                ->where('activo', true)
                ->firstOrFail();

            $this->inventarioService->registrarEntrada(
                medicamento: $medicamento,
                cantidad: $request->integer('cantidad'),
                motivo: $request->input('motivo'),
                lote: $request->input('lote'),
                fechaVencimiento: $request->input('fecha_vencimiento')
            );

            return back()->with(
                'success',
                'Entrada registrada correctamente.'
            );
        } catch (Throwable $e) {
            report($e);

            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Registrar salida del inventario general.
     */
    public function salida(
        RegistrarSalidaInventarioRequest $request
    ): RedirectResponse {
        try {
            $inventario = Inventario::findOrFail(
                $request->integer('inventario_id')
            );

            $this->inventarioService->registrarSalidaGeneral(
                inventario: $inventario,
                cantidad: $request->integer('cantidad'),
                motivo: $request->input('motivo')
            );

            return back()->with(
                'success',
                'Salida registrada correctamente.'
            );
        } catch (Throwable $e) {
            report($e);

            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }


    /**
     * Transferir medicamento al inventario individual
     * de un paciente.
     */
    public function transferir(
        TransferirInventarioRequest $request
    ): RedirectResponse {
        try {
            $inventario = Inventario::findOrFail(
                $request->integer('inventario_id')
            );

            $paciente = Paciente::findOrFail(
                $request->integer('paciente_id')
            );

            $this->inventarioService->transferirAPaciente(
                inventarioGeneral: $inventario,
                paciente: $paciente,
                cantidad: $request->integer('cantidad'),
                motivo: $request->input('motivo')
            );

            return back()->with(
                'success',
                'Medicamento transferido correctamente al paciente.'
            );
        } catch (Throwable $e) {
            report($e);

            return back()
                ->withInput()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }

    /**
     * Salida de medicamento del inventario del paciente.
     */
    public function salidaPaciente(
        Request $request,
        InventarioPaciente $inventarioPaciente
    ): RedirectResponse {
        $request->validate([
            'cantidad' => [
                'required',
                'integer',
                'min:1',
            ],

            'motivo' => [
                'nullable',
                'string',
                'max:150',
            ],
        ]);

        try {
            $this->inventarioService->registrarSalidaPaciente(
                inventarioPaciente: $inventarioPaciente,
                cantidad: $request->integer('cantidad'),
                motivo: $request->input('motivo')
            );

            return back()->with(
                'success',
                'Salida registrada correctamente.'
            );

        } catch (Throwable $e) {
            report($e);

            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }
}
