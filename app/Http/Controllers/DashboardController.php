<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use App\Models\MovimientoInventario;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        Carbon::setLocale('es');

        /*
        |--------------------------------------------------------------------------
        | CACHE LOCAL DEL DASHBOARD
        |--------------------------------------------------------------------------
        |
        | CACHE_STORE=file hace que este resumen se almacene localmente.
        | Esto evita volver a consultar Supabase cada vez que el usuario
        | sale del Dashboard y vuelve inmediatamente.
        |
        | El cache dura solamente 20 segundos para mantener los datos
        | suficientemente actualizados durante el desarrollo.
        |
        */

        $datos = Cache::remember(
            'dashboard.resumen',
            now()->addSeconds(20),
            function () {

                $hoy = Carbon::today();

                $fechaLimiteVencimiento = $hoy
                    ->copy()
                    ->addDays(30);


                /*
                |--------------------------------------------------------------------------
                | CONTADORES GENERALES
                |--------------------------------------------------------------------------
                |
                | Antes eran tres consultas independientes.
                | Ahora PostgreSQL devuelve los tres valores en un solo viaje.
                |
                */

                $contadores = DB::selectOne("
                    SELECT
                        (
                            SELECT COUNT(*)
                            FROM pacientes
                        ) AS pacientes_activos,

                        (
                            SELECT COUNT(*)
                            FROM medicamentos
                            WHERE activo = true
                        ) AS medicamentos_registrados,

                        (
                            SELECT COUNT(*)
                            FROM tratamientos
                            WHERE estado = 'activo'
                        ) AS tratamientos_activos
                ");


                $pacientesActivos =
                    (int) $contadores->pacientes_activos;

                $medicamentosRegistrados =
                    (int) $contadores->medicamentos_registrados;

                $tratamientosActivos =
                    (int) $contadores->tratamientos_activos;


                /*
                |--------------------------------------------------------------------------
                | INVENTARIO GENERAL
                |--------------------------------------------------------------------------
                |
                | Obtenemos inventario + nombre del medicamento mediante
                | una sola consulta.
                |
                */

                $inventarios = Inventario::query()
                    ->leftJoin(
                        'medicamentos',
                        'medicamentos.id',
                        '=',
                        'inventarios.medicamento_id'
                    )
                    ->select([
                        'inventarios.*',
                        'medicamentos.nombre as medicamento_nombre',
                    ])
                    ->get();


                /*
                |--------------------------------------------------------------------------
                | MEDICAMENTOS POR REPONER
                |--------------------------------------------------------------------------
                */

                $inventariosPorReponer = $inventarios
                    ->filter(function ($inventario) {
                        return $inventario->cantidad_actual
                            <= $inventario->cantidad_minima;
                    });


                $medicamentosPorReponer =
                    $inventariosPorReponer->count();


                /*
                |--------------------------------------------------------------------------
                | MEDICAMENTOS QUE REQUIEREN ATENCIÓN
                |--------------------------------------------------------------------------
                */

                $medicamentosAtencion = $inventariosPorReponer
                    ->sortBy('cantidad_actual')
                    ->take(5)
                    ->values();


                /*
                |--------------------------------------------------------------------------
                | MEDICAMENTOS AGOTADOS
                |--------------------------------------------------------------------------
                */

                $medicamentosAgotados = $inventarios
                    ->filter(function ($inventario) {
                        return $inventario->cantidad_actual == 0;
                    })
                    ->sortByDesc('updated_at')
                    ->values();


                /*
                |--------------------------------------------------------------------------
                | MEDICAMENTOS CON STOCK BAJO
                |--------------------------------------------------------------------------
                */

                $medicamentosStockBajo = $inventarios
                    ->filter(function ($inventario) {

                        return $inventario->cantidad_actual > 0
                            && $inventario->cantidad_actual
                                <= $inventario->cantidad_minima;

                    })
                    ->sortBy('cantidad_actual')
                    ->values();


                /*
                |--------------------------------------------------------------------------
                | MEDICAMENTOS PRÓXIMOS A VENCER
                |--------------------------------------------------------------------------
                */

                $medicamentosPorVencer = $inventarios
                    ->filter(
                        function ($inventario) use (
                            $hoy,
                            $fechaLimiteVencimiento
                        ) {

                            if (!$inventario->fecha_vencimiento) {
                                return false;
                            }

                            $fechaVencimiento = Carbon::parse(
                                $inventario->fecha_vencimiento
                            )->startOfDay();

                            return $fechaVencimiento->gte($hoy)
                                && $fechaVencimiento->lte(
                                    $fechaLimiteVencimiento
                                );
                        }
                    )
                    ->sortBy('fecha_vencimiento')
                    ->values();


                /*
                |--------------------------------------------------------------------------
                | MEDICAMENTOS VENCIDOS
                |--------------------------------------------------------------------------
                */

                $medicamentosVencidos = $inventarios
                    ->filter(
                        function ($inventario) use ($hoy) {

                            if (!$inventario->fecha_vencimiento) {
                                return false;
                            }

                            $fechaVencimiento = Carbon::parse(
                                $inventario->fecha_vencimiento
                            )->startOfDay();

                            return $fechaVencimiento->lt($hoy);
                        }
                    )
                    ->sortBy('fecha_vencimiento')
                    ->values();


                /*
                |--------------------------------------------------------------------------
                | ACTIVIDAD RECIENTE
                |--------------------------------------------------------------------------
                |
                | Antes Eloquent necesitaba cargar varias relaciones:
                |
                | inventario
                | inventario.medicamento
                | inventarioPaciente
                | inventarioPaciente.medicamento
                | inventarioPaciente.paciente
                | paciente
                | usuario
                |
                | Ahora todo se obtiene mediante una única consulta SQL
                | utilizando LEFT JOIN.
                |
                */

                $actividadReciente = MovimientoInventario::query()

                    /*
                    |--------------------------------------------------------------------------
                    | INVENTARIO GENERAL
                    |--------------------------------------------------------------------------
                    */

                    ->leftJoin(
                        'inventarios as inv_general',
                        'inv_general.id',
                        '=',
                        'movimiento_inventarios.inventario_id'
                    )

                    ->leftJoin(
                        'medicamentos as med_general',
                        'med_general.id',
                        '=',
                        'inv_general.medicamento_id'
                    )


                    /*
                    |--------------------------------------------------------------------------
                    | INVENTARIO DEL PACIENTE
                    |--------------------------------------------------------------------------
                    */

                    ->leftJoin(
                        'inventarios_pacientes as inv_paciente',
                        'inv_paciente.id',
                        '=',
                        'movimiento_inventarios.inventario_paciente_id'
                    )

                    ->leftJoin(
                        'medicamentos as med_paciente',
                        'med_paciente.id',
                        '=',
                        'inv_paciente.medicamento_id'
                    )


                    /*
                    |--------------------------------------------------------------------------
                    | PACIENTE
                    |--------------------------------------------------------------------------
                    */

                    ->leftJoin(
                        'pacientes as pac_directo',
                        'pac_directo.id',
                        '=',
                        'movimiento_inventarios.paciente_id'
                    )

                    ->leftJoin(
                        'pacientes as pac_inventario',
                        'pac_inventario.id',
                        '=',
                        'inv_paciente.paciente_id'
                    )


                    /*
                    |--------------------------------------------------------------------------
                    | USUARIO
                    |--------------------------------------------------------------------------
                    */

                    ->leftJoin(
                        'users',
                        'users.id',
                        '=',
                        'movimiento_inventarios.usuario_id'
                    )


                    /*
                    |--------------------------------------------------------------------------
                    | CAMPOS NECESARIOS PARA EL DASHBOARD
                    |--------------------------------------------------------------------------
                    */

                    ->select([
                        'movimiento_inventarios.id',
                        'movimiento_inventarios.tipo_movimiento',
                        'movimiento_inventarios.cantidad',
                        'movimiento_inventarios.fecha',

                        DB::raw("
                            COALESCE(
                                med_general.nombre,
                                med_paciente.nombre,
                                'Medicamento'
                            ) AS medicamento_nombre
                        "),

                        DB::raw("
                            COALESCE(
                                pac_directo.nombre,
                                pac_inventario.nombre
                            ) AS paciente_nombre
                        "),

                        DB::raw("
                            COALESCE(
                                pac_directo.apellido,
                                pac_inventario.apellido
                            ) AS paciente_apellido
                        "),

                        'users.nombre as usuario_nombre',
                        'users.apellido as usuario_apellido',
                        'users.rol as usuario_rol',
                    ])

                    ->orderByDesc(
                        'movimiento_inventarios.fecha'
                    )

                    ->limit(5)

                    ->get();


                /*
                |--------------------------------------------------------------------------
                | DATOS DEL DASHBOARD
                |--------------------------------------------------------------------------
                */

                return compact(
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
                );
            }
        );


        /*
        |--------------------------------------------------------------------------
        | MOSTRAR DASHBOARD
        |--------------------------------------------------------------------------
        */

        return view(
            'dashboard.index',
            $datos
        );
    }
}
