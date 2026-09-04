@extends('layouts.app')

@section('title', 'Inventario')

@section('content')

<div class="container-fluid py-4">

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">

        <div>
            <h1 class="fw-bold mb-1"
                style="color:#0f172a;">
                Inventario de medicamentos
            </h1>

            <p class="text-muted mb-0">
                Inventario general de la residencia e inventario por paciente.
            </p>
        </div>

        <div class="d-flex flex-wrap gap-2">

            <button
                type="button"
                class="btn btn-success"
                data-bs-toggle="modal"
                data-bs-target="#modalEntrada"
            >
                <i class="bi bi-box-arrow-in-down me-1"></i>
                Registrar entrada
            </button>

            <button
                type="button"
                class="btn btn-outline-secondary"
                data-bs-toggle="modal"
                data-bs-target="#modalSalida"
            >
                <i class="bi bi-box-arrow-up me-1"></i>
                Registrar salida
            </button>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- ALERTAS --}}
    {{-- ========================================================= --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>
        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle me-2"></i>

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>
        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- TABS PRINCIPALES --}}
    {{-- ========================================================= --}}

    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-body p-2">

            <div class="d-flex gap-2">

                <a
                    href="{{ route('inventario.index') }}"
                    class="btn rounded-3 px-4 py-3
                    {{ request()->routeIs('inventario.index')
                        ? 'text-white'
                        : 'btn-light' }}"
                    style="
                        {{ request()->routeIs('inventario.index')
                            ? 'background:#0f2d6b;'
                            : '' }}
                    "
                >
                    <i class="bi bi-capsule me-2"></i>

                    Ver inventario general
                </a>

                <a
                    href="{{ route('inventario.pacientes') }}"
                    class="btn rounded-3 px-4 py-3
                    {{ request()->routeIs('inventario.pacientes')
                        || request()->routeIs('inventario.paciente')
                        ? 'text-white'
                        : 'btn-light' }}"
                    style="
                        {{ request()->routeIs('inventario.pacientes')
                            || request()->routeIs('inventario.paciente')
                            ? 'background:#0f2d6b;'
                            : '' }}
                    "
                >
                    <i class="bi bi-person me-2"></i>

                    Ver inventario por paciente
                </a>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- LAYOUT --}}
    {{-- ========================================================= --}}

    <div class="row g-4">

        {{-- ===================================================== --}}
        {{-- IZQUIERDA --}}
        {{-- ===================================================== --}}

        <div class="col-xl-8">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body">

                    {{-- BUSCADOR --}}

                    <form
                        method="GET"
                        action="{{ route('inventario.index') }}"
                        class="row g-2 mb-4"
                    >

                        <div class="col-lg-6">

                            <div class="input-group">

                                <span class="input-group-text bg-white">
                                    <i class="bi bi-search"></i>
                                </span>

                                <input
                                    type="text"
                                    name="buscar"
                                    value="{{ $buscar }}"
                                    class="form-control"
                                    placeholder="Buscar medicamento..."
                                >

                            </div>

                        </div>

                        <div class="col-lg-6">

                            <div class="d-flex flex-wrap gap-2">

                                @php
                                    $filtros = [
                                        'todos' => 'Todos',
                                        'disponibles' => 'Disponibles',
                                        'stock_bajo' => 'Stock bajo',
                                        'agotados' => 'Próximos a agotarse',
                                        'vencidos' => 'Vencidos',
                                    ];
                                @endphp

                                @foreach($filtros as $valor => $nombre)

                                    <button
                                        type="submit"
                                        name="filtro"
                                        value="{{ $valor }}"
                                        class="btn btn-sm rounded-pill
                                        {{ $filtro === $valor
                                            ? 'text-white'
                                            : 'btn-light border' }}"
                                        style="
                                            {{ $filtro === $valor
                                                ? 'background:#0f2d6b;'
                                                : '' }}
                                        "
                                    >
                                        {{ $nombre }}
                                    </button>

                                @endforeach

                            </div>

                        </div>

                    </form>


                    {{-- TABLA --}}

                    <div class="table-responsive">

                        <table class="table align-middle">

                            <thead>

                                <tr class="text-muted small">

                                    <th>MEDICAMENTO</th>

                                    <th>PRESENTACIÓN</th>

                                    <th>STOCK ACTUAL</th>

                                    <th>STOCK MÍNIMO</th>

                                    <th>VENCIMIENTO</th>

                                    <th>ESTADO</th>

                                    <th></th>

                                </tr>

                            </thead>

                            <tbody>

                                @forelse($inventarios as $inventario)

                                    @php

                                        $vencido =
                                            $inventario->fecha_vencimiento
                                            &&
                                            $inventario->fecha_vencimiento->isPast();

                                        if (
                                            $inventario->cantidad_actual <= 0
                                        ) {
                                            $estadoTexto = 'Reposición necesaria';
                                            $estadoClase = 'danger';
                                        } elseif (
                                            $inventario->cantidad_actual
                                            <=
                                            $inventario->cantidad_minima
                                        ) {
                                            $estadoTexto = 'Stock bajo';
                                            $estadoClase = 'warning';
                                        } else {
                                            $estadoTexto = 'Disponible';
                                            $estadoClase = 'success';
                                        }

                                    @endphp

                                    <tr>

                                        <td>

                                            <div class="fw-semibold"
                                                style="color:#0f172a;">

                                                {{ $inventario->medicamento->nombre }}

                                            </div>

                                            @if($inventario->medicamento->concentracion)

                                                <small class="text-muted">
                                                    {{ $inventario->medicamento->concentracion }}
                                                </small>

                                            @endif

                                        </td>

                                        <td>
                                            {{ $inventario->medicamento->presentacion }}
                                        </td>

                                        <td class="fw-bold">
                                            {{ $inventario->cantidad_actual }}
                                        </td>

                                        <td>
                                            {{ $inventario->cantidad_minima }}
                                        </td>

                                        <td>

                                            @if($inventario->fecha_vencimiento)

                                                {{ $inventario->fecha_vencimiento->format('m/Y') }}

                                                @if($vencido)

                                                    <div class="text-danger small fw-semibold">
                                                        Vencido
                                                    </div>

                                                @endif

                                            @else

                                                <span class="text-muted">
                                                    —
                                                </span>

                                            @endif

                                        </td>

                                        <td>

                                            <span
                                                class="badge rounded-pill text-bg-{{ $estadoClase }}"
                                            >
                                                {{ $estadoTexto }}
                                            </span>

                                        </td>

                                        <td>

                                            <a
                                                href="{{ route(
                                                    'inventario.index',
                                                    array_merge(
                                                        request()->query(),
                                                        ['medicamento' => $inventario->id]
                                                    )
                                                ) }}"
                                                class="text-decoration-none fw-semibold"
                                                style="color:#059669;"
                                            >
                                                Ver detalle
                                            </a>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td
                                            colspan="7"
                                            class="text-center py-5 text-muted"
                                        >

                                            <i class="bi bi-box-seam fs-1 d-block mb-3"></i>

                                            No existen medicamentos que coincidan
                                            con los filtros seleccionados.

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>


                    {{-- PAGINACIÓN --}}

                    <div class="mt-3">

                        {{ $inventarios->links() }}

                    </div>

                </div>

            </div>

        </div>


        {{-- ===================================================== --}}
        {{-- DERECHA --}}
        {{-- ===================================================== --}}

        <div class="col-xl-4">

            <div class="card border-0 shadow-sm rounded-4 h-100">

                @if($detalle)

                    @include(
                        'inventario.partials.detalle-medicamento',
                        ['detalle' => $detalle]
                    )

                @else

                    <div class="card-body
                                d-flex
                                flex-column
                                justify-content-center
                                align-items-center
                                text-center
                                p-5">

                        <div
                            class="rounded-circle d-flex align-items-center justify-content-center mb-3"
                            style="
                                width:70px;
                                height:70px;
                                background:#eff6ff;
                                color:#0f2d6b;
                            "
                        >
                            <i class="bi bi-capsule fs-3"></i>
                        </div>

                        <h5
                            class="fw-bold"
                            style="color:#0f172a;"
                        >
                            Detalle del medicamento
                        </h5>

                        <p class="text-muted mb-0">

                            Selecciona
                            <strong>
                                "Ver detalle"
                            </strong>
                            en cualquier medicamento para revisar
                            stock, movimientos y duración estimada.

                        </p>

                    </div>

                @endif

            </div>

        </div>

    </div>

</div>


{{-- MODALES --}}

@include('inventario.partials.modal-entrada')

@include('inventario.partials.modal-salida')

@include('inventario.partials.modal-transferencia')


@push('scripts')

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const modalSalida =
            document.getElementById('modalSalida');

        if (modalSalida) {

            modalSalida.addEventListener(
                'show.bs.modal',
                function (event) {

                    const button =
                        event.relatedTarget;

                    if (!button) {
                        return;
                    }

                    const inventarioId =
                        button.getAttribute(
                            'data-inventario-id'
                        );

                    const input =
                        document.getElementById(
                            'salida_inventario_id'
                        );

                    if (input) {
                        input.value = inventarioId || '';
                    }

                }
            );

        }


        const modalTransferencia =
            document.getElementById(
                'modalTransferencia'
            );

        if (modalTransferencia) {

            modalTransferencia.addEventListener(
                'show.bs.modal',
                function (event) {

                    const button =
                        event.relatedTarget;

                    if (!button) {
                        return;
                    }

                    const inventarioId =
                        button.getAttribute(
                            'data-inventario-id'
                        );

                    const medicamento =
                        button.getAttribute(
                            'data-medicamento'
                        );

                    document.getElementById(
                        'transferencia_inventario_id'
                    ).value =
                        inventarioId || '';

                    document.getElementById(
                        'transferencia_medicamento_nombre'
                    ).textContent =
                        medicamento || '';

                }
            );

        }

    }
);

</script>

@endpush
@endsection
