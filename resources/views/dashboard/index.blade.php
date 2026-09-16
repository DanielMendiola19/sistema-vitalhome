@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4 gap-2">

        <div>

            @php
                $hora = now()->hour;

                $saludo = match (true) {
                    $hora < 12 => 'Buenos días',
                    $hora < 19 => 'Buenas tardes',
                    default => 'Buenas noches',
                };
            @endphp

            <h2 class="fw-bold text-primary-vital mb-1">
                {{ $saludo }},
                {{ Auth::user()->nombre }}
                {{ Auth::user()->apellido }}
            </h2>

            <p class="text-secondary-vital mb-0">
                Resumen general del sistema
            </p>

        </div>

        <button
            type="button"
            class="btn btn-outline-secondary position-relative"
            data-bs-toggle="modal"
            data-bs-target="#modalAlertas"
        >
            <i class="bi bi-exclamation-triangle me-2"></i>
            Ver alertas

            @php
                $totalAlertas =
                    $medicamentosAgotados->count()
                    + $medicamentosStockBajo->count()
                    + $medicamentosPorVencer->count()
                    + $medicamentosVencidos->count();
            @endphp

            @if($totalAlertas > 0)

                <span
                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                >
                    {{ $totalAlertas }}
                </span>

            @endif

        </button>

    </div>


    {{-- ====================================================== --}}
    {{-- TARJETAS DE ESTADÍSTICAS --}}
    {{-- ====================================================== --}}

    <div class="row g-3 mb-4">

        <div class="col-12 col-sm-6 col-xl-3">

            <div class="card stat-card stat-pacientes h-100">

                <div class="card-body">

                    <div class="stat-content">

                        <div class="stat-icon stat-icon-blue">
                            <i class="bi bi-people-fill"></i>
                        </div>

                        <div class="stat-info">

                            <span class="stat-label">
                                Pacientes activos
                            </span>

                            <h3>
                                {{ $pacientesActivos }}
                            </h3>

                            <small>
                                Registrados actualmente
                            </small>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-12 col-sm-6 col-xl-3">

            <div class="card stat-card stat-medicamentos h-100">

                <div class="card-body">

                    <div class="stat-content">

                        <div class="stat-icon stat-icon-green">
                            <i class="bi bi-capsule-pill"></i>
                        </div>

                        <div class="stat-info">

                            <span class="stat-label">
                                Medicamentos
                            </span>

                            <h3>
                                {{ $medicamentosRegistrados }}
                            </h3>

                            <small>
                                Registrados actualmente
                            </small>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-12 col-sm-6 col-xl-3">

            <div class="card stat-card stat-reposicion h-100">

                <div class="card-body">

                    <div class="stat-content">

                        <div class="stat-icon stat-icon-red">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                        </div>

                        <div class="stat-info">

                            <span class="stat-label">
                                Por reponer
                            </span>

                            <h3>
                                {{ $medicamentosPorReponer }}
                            </h3>

                            <small>
                                Requieren atención
                            </small>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-12 col-sm-6 col-xl-3">

            <div class="card stat-card stat-tratamientos h-100">

                <div class="card-body">

                    <div class="stat-content">

                        <div class="stat-icon stat-icon-orange">
                            <i class="bi bi-clipboard2-check-fill"></i>
                        </div>

                        <div class="stat-info">

                            <span class="stat-label">
                                Tratamientos activos
                            </span>

                            <h3>
                                {{ $tratamientosActivos }}
                            </h3>

                            <small>
                                En seguimiento
                            </small>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ====================================================== --}}
    {{-- CONTENIDO PRINCIPAL --}}
    {{-- ====================================================== --}}

    <div class="row g-4">

        {{-- MEDICAMENTOS QUE REQUIEREN ATENCIÓN --}}

        <div class="col-lg-8">

            <div class="card section-card">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-3">

                        <div>

                            <h5 class="fw-bold mb-1">
                                Medicamentos que requieren atención
                            </h5>

                            <small class="text-secondary-vital">
                                Basado en stock actual y consumo estimado
                            </small>

                        </div>


                        {{-- DOCTOR PUEDE VER LA INFORMACIÓN,
                             PERO NO ENTRAR AL INVENTARIO --}}

                        @if(Auth::user()->rol !== 'doctor')

                            <a
                                href="{{ route('inventario.index') }}"
                                class="text-decoration-none"
                                style="color:#388E3C;"
                            >
                                Ver inventario →
                            </a>

                        @endif

                    </div>


                    <div class="table-responsive">

                        <table class="table align-middle">

                            <thead class="table-light">

                                <tr>
                                    <th>MEDICAMENTO</th>
                                    <th>PACIENTE / GENERAL</th>
                                    <th>STOCK</th>
                                    <th>CONSUMO</th>
                                    <th>ESTADO</th>
                                </tr>

                            </thead>

                            <tbody>

                                @forelse($medicamentosAtencion as $inventario)

                                    <tr>

                                        <td class="fw-semibold">
                                            {{ $inventario->medicamento_nombre }}
                                        </td>

                                        <td>
                                            General
                                        </td>

                                        <td>
                                            {{ $inventario->cantidad_actual }}
                                        </td>

                                        <td>
                                            —
                                        </td>

                                        <td>

                                            @if(
                                                $inventario->cantidad_actual
                                                <= $inventario->cantidad_minima
                                            )

                                                <span
                                                    class="badge rounded-pill"
                                                    style="background:#FFEDED;color:#D32F2F;"
                                                >
                                                    ● Reposición necesaria
                                                </span>

                                            @else

                                                <span
                                                    class="badge rounded-pill"
                                                    style="background:#FFFDE7;color:#FF9800;"
                                                >
                                                    ● Stock bajo
                                                </span>

                                            @endif

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td
                                            colspan="5"
                                            class="text-center text-secondary py-4"
                                        >
                                            No hay medicamentos que requieran atención.
                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>


        {{-- ACTIVIDAD RECIENTE --}}

        <div class="col-lg-4">

            <div class="card section-card h-100">

                <div class="card-body">

                    <h5 class="fw-bold mb-1">
                        Actividad reciente
                    </h5>

                    <small class="text-secondary-vital">
                        Últimos movimientos registrados
                    </small>

                    <div class="mt-4">

                        @forelse($actividadReciente as $movimiento)

                            @php

                                $tipo = strtolower(
                                    $movimiento->tipo_movimiento
                                );

                                $configuracion = match ($tipo) {

                                    'entrada' => [
                                        'icono' => 'bi-box-arrow-in-down',
                                        'texto' => 'Entrada',
                                        'clase' => 'text-success',
                                    ],

                                    'salida' => [
                                        'icono' => 'bi-box-arrow-up',
                                        'texto' => 'Salida',
                                        'clase' => 'text-danger',
                                    ],

                                    'transferencia' => [
                                        'icono' => 'bi-arrow-left-right',
                                        'texto' => 'Transferencia',
                                        'clase' => 'text-primary',
                                    ],

                                    default => [
                                        'icono' => 'bi-arrow-repeat',
                                        'texto' => ucfirst($tipo),
                                        'clase' => 'text-secondary',
                                    ],
                                };

                            @endphp


                            <div class="d-flex mb-4">

                                <div
                                    class="me-3 {{ $configuracion['clase'] }}"
                                >
                                    <i
                                        class="bi {{ $configuracion['icono'] }} fs-5"
                                    ></i>
                                </div>


                                <div class="flex-grow-1">

                                    <div class="small fw-semibold">

                                        {{ $configuracion['texto'] }}

                                        de {{ $movimiento->cantidad }}

                                        {{
                                            $movimiento->cantidad == 1
                                                ? 'unidad'
                                                : 'unidades'
                                        }}

                                        de {{ $movimiento->medicamento_nombre }}

                                    </div>


                                    @if($movimiento->paciente_nombre)

                                        <div class="small text-secondary-vital mt-1">

                                            <i class="bi bi-person-heart me-1"></i>

                                            Paciente:

                                            {{ $movimiento->paciente_nombre }}

                                            {{ $movimiento->paciente_apellido }}

                                        </div>

                                    @endif


                                    @if($movimiento->usuario_nombre)

                                        <div class="small text-secondary-vital mt-1">

                                            <i class="bi bi-person-fill me-1"></i>

                                            Realizado por:

                                            {{ ucfirst($movimiento->usuario_rol) }}

                                            {{ $movimiento->usuario_nombre }}

                                            {{ $movimiento->usuario_apellido }}

                                        </div>

                                    @endif


                                    <div class="small text-secondary-vital mt-1">

                                        <i class="bi bi-clock me-1"></i>

                                        {{ $movimiento->fecha?->diffForHumans() }}

                                    </div>

                                </div>

                            </div>

                        @empty

                            <div class="text-center text-secondary py-4">

                                <i
                                    class="bi bi-clock-history fs-3 d-block mb-2"
                                ></i>

                                No hay movimientos recientes.

                            </div>

                        @endforelse

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- ========================================================== --}}
{{-- MODAL DE ALERTAS --}}
{{-- ========================================================== --}}

<div
    class="modal fade"
    id="modalAlertas"
    tabindex="-1"
    aria-labelledby="modalAlertasLabel"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered modal-lg">

        <div class="modal-content border-0 rounded-4 shadow">


            {{-- HEADER --}}

            <div class="modal-header border-0">

                <div>

                    <h5
                        class="modal-title fw-bold"
                        id="modalAlertasLabel"
                        style="color:#0f172a;"
                    >

                        <i
                            class="bi bi-exclamation-triangle-fill text-warning me-2"
                        ></i>

                        Alertas del sistema

                    </h5>

                    <small class="text-secondary-vital">
                        Situación actual del inventario
                    </small>

                </div>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>


            {{-- BODY --}}

            <div class="modal-body">


                {{-- ====================================================== --}}
                {{-- MEDICAMENTOS AGOTADOS --}}
                {{-- ====================================================== --}}

                @if($medicamentosAgotados->count() > 0)

                    <div class="mb-4">

                        <h6 class="fw-bold text-danger mb-3">

                            <i class="bi bi-x-circle-fill me-1"></i>

                            Medicamentos agotados

                        </h6>


                        @foreach($medicamentosAgotados as $inventario)

                            <div
                                class="d-flex justify-content-between align-items-center p-3 mb-2 rounded-3"
                                style="background:#FFEDED;"
                            >

                                <div>

                                    <div class="fw-semibold">
                                        {{ $inventario->medicamento_nombre }}
                                    </div>

                                    <small class="text-secondary-vital">
                                        Stock actual: 0 unidades
                                    </small>

                                </div>


                                <span class="badge rounded-pill bg-danger">
                                    Agotado
                                </span>

                            </div>

                        @endforeach

                    </div>

                @endif


                {{-- ====================================================== --}}
                {{-- STOCK BAJO --}}
                {{-- ====================================================== --}}

                @if($medicamentosStockBajo->count() > 0)

                    <div>

                        <h6
                            class="fw-bold mb-3"
                            style="color:#FF9800;"
                        >

                            <i
                                class="bi bi-exclamation-circle-fill me-1"
                            ></i>

                            Stock bajo

                        </h6>


                        @foreach($medicamentosStockBajo as $inventario)

                            <div
                                class="d-flex justify-content-between align-items-center p-3 mb-2 rounded-3"
                                style="background:#FFF8E1;"
                            >

                                <div>

                                    <div class="fw-semibold">
                                        {{ $inventario->medicamento_nombre }}
                                    </div>

                                    <small class="text-secondary-vital">

                                        Stock actual:
                                        {{ $inventario->cantidad_actual }}
                                        unidades

                                        · Mínimo:
                                        {{ $inventario->cantidad_minima }}

                                    </small>

                                </div>


                                <span
                                    class="badge rounded-pill"
                                    style="background:#FFF3CD;color:#FF9800;"
                                >
                                    Stock bajo
                                </span>

                            </div>

                        @endforeach

                    </div>

                @endif


                {{-- ====================================================== --}}
                {{-- PRÓXIMOS A VENCER --}}
                {{-- ====================================================== --}}

                @if($medicamentosPorVencer->count() > 0)

                    <div class="mt-4">

                        <h6
                            class="fw-bold mb-3"
                            style="color:#FF9800;"
                        >

                            <i class="bi bi-calendar-x-fill me-1"></i>

                            Próximos a vencer

                        </h6>


                        @foreach($medicamentosPorVencer as $inventario)

                            @php

                                $diasRestantes = now()
                                    ->startOfDay()
                                    ->diffInDays(
                                        $inventario->fecha_vencimiento,
                                        false
                                    );

                            @endphp


                            <div
                                class="d-flex justify-content-between align-items-center p-3 mb-2 rounded-3"
                                style="background:#FFF8E1;"
                            >

                                <div>

                                    <div class="fw-semibold">
                                        {{ $inventario->medicamento_nombre }}
                                    </div>

                                    <small class="text-secondary-vital">

                                        Vence:

                                        {{
                                            $inventario
                                                ->fecha_vencimiento
                                                ->format('d/m/Y')
                                        }}

                                        ·

                                        @if($diasRestantes === 0)

                                            vence hoy

                                        @elseif($diasRestantes === 1)

                                            1 día restante

                                        @else

                                            {{ $diasRestantes }} días restantes

                                        @endif

                                    </small>

                                </div>


                                <span
                                    class="badge rounded-pill"
                                    style="background:#FFF3CD;color:#FF9800;"
                                >
                                    Próximo a vencer
                                </span>

                            </div>

                        @endforeach

                    </div>

                @endif


                {{-- ====================================================== --}}
                {{-- MEDICAMENTOS VENCIDOS --}}
                {{-- ====================================================== --}}

                @if($medicamentosVencidos->count() > 0)

                    <div class="mb-4">

                        <h6 class="fw-bold text-danger mb-3">

                            <i class="bi bi-calendar-x-fill me-1"></i>

                            Medicamentos vencidos

                        </h6>


                        @foreach($medicamentosVencidos as $inventario)

                            <div
                                class="d-flex justify-content-between align-items-center p-3 mb-2 rounded-3"
                                style="background:#FFEDED;"
                            >

                                <div>

                                    <div class="fw-semibold">
                                        {{ $inventario->medicamento_nombre }}
                                    </div>

                                    <small class="text-secondary-vital">

                                        Venció:

                                        {{
                                            $inventario
                                                ->fecha_vencimiento
                                                ->format('d/m/Y')
                                        }}

                                        · Stock actual:

                                        {{ $inventario->cantidad_actual }}

                                        unidades

                                    </small>

                                </div>


                                <span class="badge rounded-pill bg-danger">
                                    Vencido
                                </span>

                            </div>

                        @endforeach

                    </div>

                @endif


                {{-- ====================================================== --}}
                {{-- SIN ALERTAS --}}
                {{-- ====================================================== --}}

                @if($totalAlertas === 0)

                    <div class="text-center py-5">

                        <i
                            class="bi bi-check-circle-fill text-success"
                            style="font-size:3rem;"
                        ></i>

                        <h6 class="fw-bold mt-3 mb-1">
                            Todo está en orden
                        </h6>

                        <p class="text-secondary-vital mb-0">
                            No existen alertas de inventario actualmente.
                        </p>

                    </div>

                @endif

            </div>


            {{-- FOOTER --}}

            <div class="modal-footer border-0">


                {{-- DOCTOR NO PUEDE ENTRAR A INVENTARIO --}}

                @if(Auth::user()->rol !== 'doctor')

                    <a
                        href="{{ route('inventario.index') }}"
                        class="btn btn-success"
                    >

                        <i class="bi bi-box-seam me-1"></i>

                        Ir al inventario

                    </a>

                @endif


                <button
                    type="button"
                    class="btn btn-light"
                    data-bs-dismiss="modal"
                >
                    Cerrar
                </button>

            </div>

        </div>

    </div>

</div>

@endsection
