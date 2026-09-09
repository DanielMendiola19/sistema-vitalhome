@extends('layouts.app')

@section('title', 'Inventario del paciente')

@section('content')

<div class="container-fluid py-4">

{{-- ENCABEZADO --}}
<div class="d-flex justify-content-between align-items-start mb-4">

    <div>
        <a
            href="{{ route('inventario.pacientes') }}"
            class="text-decoration-none mb-3 d-inline-block"
            style="color:#059669;"
        >
            <i class="bi bi-arrow-left me-1"></i>
            Cambiar paciente
        </a>

        <h1
            class="fw-bold mb-1"
            style="color:#0f172a;"
        >
            Medicamentos de
            {{ $paciente->nombre }}
            {{ $paciente->apellido }}
        </h1>

        <p class="text-muted mb-0">
            @if($paciente->fecha_nacimiento)
                {{ $paciente->fecha_nacimiento->age }} años
            @endif
            · Inventario individual
        </p>
    </div>

    <div>
        <button
            type="button"
            class="btn btn-success rounded-3"
            data-bs-toggle="modal"
            data-bs-target="#modalRegistrarMedicamentoPaciente"
        >
            <i class="bi bi-plus-lg me-1"></i>
            Registrar medicamento
        </button>
    </div>

</div>


{{-- INVENTARIO DE MEDICAMENTOS --}}
<div class="row g-4">

    <div class="col-12">

        <div class="card border-0 shadow-sm rounded-4">

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table align-middle">

                        <thead>

                            <tr class="small text-muted">

                                <th>MEDICAMENTO</th>
                                <th>DOSIS</th>
                                <th>FRECUENCIA</th>
                                <th>VÍA</th>
                                <th>STOCK ACTUAL</th>
                                <th>CONSUMO DIARIO</th>
                                <th>DÍAS RESTANTES</th>
                                <th>VENCIMIENTO</th>
                                <th>ESTADO</th>
                                <th>ACCIÓN</th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse(
                                $paciente->inventariosPacientes
                                as $inventario
                            )

                                @php

                                    $tratamiento =
                                        $paciente->tratamientos
                                            ->where(
                                                'medicamento_id',
                                                $inventario->medicamento_id
                                            )
                                            ->where(
                                                'estado',
                                                'activo'
                                            )
                                            ->first();

                                    $consumoDiario = null;

                                    if ($tratamiento) {

                                        $frecuencia =
                                            strtolower(
                                                $tratamiento->frecuencia
                                            );

                                        if (
                                            str_contains(
                                                $frecuencia,
                                                '12'
                                            )
                                        ) {
                                            $consumoDiario = 2;

                                        } elseif (
                                            str_contains(
                                                $frecuencia,
                                                '24'
                                            )
                                        ) {
                                            $consumoDiario = 1;

                                        } elseif (
                                            str_contains(
                                                $frecuencia,
                                                '8'
                                            )
                                        ) {
                                            $consumoDiario = 3;

                                        } elseif (
                                            str_contains(
                                                $frecuencia,
                                                '6'
                                            )
                                        ) {
                                            $consumoDiario = 4;

                                        } elseif (
                                            str_contains(
                                                $frecuencia,
                                                'día'
                                            )
                                            ||
                                            str_contains(
                                                $frecuencia,
                                                'diaria'
                                            )
                                        ) {
                                            $consumoDiario = 1;
                                        }

                                    }

                                    $diasRestantes =
                                        $consumoDiario > 0
                                            ? floor(
                                                $inventario->cantidad_actual
                                                /
                                                $consumoDiario
                                            )
                                            : null;

                                    if (
                                        $inventario->cantidad_actual <= 0
                                    ) {

                                        $estado =
                                            'Reposición necesaria';

                                        $clase =
                                            'danger';

                                    } elseif (
                                        $inventario->cantidad_actual
                                        <=
                                        $inventario->cantidad_minima
                                    ) {

                                        $estado =
                                            'Stock bajo';

                                        $clase =
                                            'warning';

                                    } else {

                                        $estado =
                                            'Disponible';

                                        $clase =
                                            'success';

                                    }

                                @endphp


                                <tr>

                                    {{-- MEDICAMENTO --}}
                                    <td class="fw-semibold">

                                        {{ $inventario->medicamento->nombre }}

                                        @if(
                                            $inventario->medicamento->concentracion
                                        )

                                            <div class="small text-muted">
                                                {{ $inventario->medicamento->concentracion }}
                                            </div>

                                        @endif

                                    </td>


                                    {{-- DOSIS --}}
                                    <td>

                                        {{ $tratamiento?->dosis ?? '—' }}

                                    </td>


                                    {{-- FRECUENCIA --}}
                                    <td>

                                        {{ $tratamiento?->frecuencia ?? '—' }}

                                    </td>


                                    {{-- VÍA --}}
                                    <td>

                                        {{ $tratamiento?->via_administracion ?? '—' }}

                                    </td>


                                    {{-- STOCK --}}
                                    <td class="fw-bold">

                                        {{ $inventario->cantidad_actual }}

                                    </td>


                                    {{-- CONSUMO DIARIO --}}
                                    <td>

                                        @if($consumoDiario)

                                            {{ $consumoDiario }} /día

                                        @else

                                            —

                                        @endif

                                    </td>


                                    {{-- DÍAS RESTANTES --}}
                                    <td>

                                        @if($diasRestantes !== null)

                                            {{ $diasRestantes }} días

                                        @else

                                            —

                                        @endif

                                    </td>


                                    {{-- VENCIMIENTO --}}
                                    <td>

                                        @if($inventario->fecha_vencimiento)

                                            {{ $inventario->fecha_vencimiento->format('m/Y') }}

                                            @if(
                                                $inventario->fecha_vencimiento->isPast()
                                            )

                                                <div class="text-danger small">
                                                    Vencido
                                                </div>

                                            @endif

                                        @else

                                            —

                                        @endif

                                    </td>


                                    {{-- ESTADO --}}
                                    <td>

                                        <span
                                            class="badge rounded-pill text-bg-{{ $clase }}"
                                        >
                                            {{ $estado }}
                                        </span>

                                    </td>


                                    {{-- ACCIÓN --}}
                                    <td>

                                        <button
                                            type="button"
                                            class="btn btn-sm btn-link text-success text-decoration-none fw-semibold"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalSalidaPaciente{{ $inventario->id }}"
                                        >
                                            Ver detalle
                                        </button>

                                    </td>

                                </tr>


                                {{-- MODAL SALIDA PACIENTE --}}
                                <div
                                    class="modal fade"
                                    id="modalSalidaPaciente{{ $inventario->id }}"
                                    tabindex="-1"
                                    aria-hidden="true"
                                >

                                    <div class="modal-dialog modal-dialog-centered">

                                        <div class="modal-content border-0 rounded-4 shadow">

                                            <form
                                                method="POST"
                                                action="{{ route(
                                                    'inventario.paciente.salida',
                                                    $inventario
                                                ) }}"
                                            >

                                                @csrf


                                                <div class="modal-header border-0">

                                                    <div>

                                                        <h5 class="fw-bold mb-1">

                                                            {{ $inventario->medicamento->nombre }}

                                                        </h5>

                                                        <small class="text-muted">

                                                            Inventario de
                                                            {{ $paciente->nombre }}
                                                            {{ $paciente->apellido }}

                                                        </small>

                                                    </div>


                                                    <button
                                                        type="button"
                                                        class="btn-close"
                                                        data-bs-dismiss="modal"
                                                    ></button>

                                                </div>


                                                <div class="modal-body">

                                                    {{-- STOCK ACTUAL --}}
                                                    <div class="mb-3">

                                                        <label class="form-label">
                                                            Stock actual
                                                        </label>

                                                        <input
                                                            type="text"
                                                            class="form-control"
                                                            value="{{ $inventario->cantidad_actual }}"
                                                            disabled
                                                        >

                                                    </div>


                                                    {{-- CANTIDAD --}}
                                                    <div class="mb-3">

                                                        <label class="form-label">
                                                            Cantidad a retirar
                                                        </label>

                                                        <input
                                                            type="number"
                                                            name="cantidad"
                                                            min="1"
                                                            max="{{ $inventario->cantidad_actual }}"
                                                            class="form-control"
                                                            required
                                                        >

                                                    </div>


                                                    {{-- MOTIVO --}}
                                                    <div>

                                                        <label class="form-label">
                                                            Motivo
                                                        </label>

                                                        <input
                                                            type="text"
                                                            name="motivo"
                                                            maxlength="150"
                                                            class="form-control"
                                                            value="Administración"
                                                        >

                                                    </div>

                                                </div>


                                                <div class="modal-footer border-0">

                                                    <button
                                                        type="button"
                                                        class="btn btn-light"
                                                        data-bs-dismiss="modal"
                                                    >
                                                        Cancelar
                                                    </button>


                                                    <button
                                                        type="submit"
                                                        class="btn btn-success"
                                                    >
                                                        Registrar salida
                                                    </button>

                                                </div>

                                            </form>

                                        </div>

                                    </div>

                                </div>

                            @empty

                                <tr>

                                    <td
                                        colspan="10"
                                        class="text-center py-5 text-muted"
                                    >

                                        <i
                                            class="bi bi-capsule fs-1 d-block mb-3"
                                        ></i>

                                        Este paciente todavía no tiene
                                        medicamentos asignados.

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- HISTORIAL DE MOVIMIENTOS --}}
<div class="row g-4 mt-1">

    <div class="col-12">

        <div class="card border-0 shadow-sm rounded-4">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center mb-4">

                    <div>

                        <h5
                            class="fw-bold mb-1"
                            style="color:#0f172a;"
                        >
                            Historial de movimientos
                        </h5>

                        <p class="text-muted mb-0 small">
                            Registro de entradas, salidas y transferencias
                            de medicamentos del paciente.
                        </p>

                    </div>


                    <i
                        class="bi bi-clock-history fs-3"
                        style="color:#059669;"
                    ></i>

                </div>


                <div class="table-responsive">

                    <table class="table align-middle">

                        <thead>

                            <tr class="small text-muted">

                                <th>FECHA</th>
                                <th>MEDICAMENTO</th>
                                <th>TIPO</th>
                                <th>CANTIDAD</th>
                                <th>MOTIVO</th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse(
                                $paciente->movimientosInventario
                                as $movimiento
                            )

                                <tr>

                                    {{-- FECHA --}}
                                    <td>

                                        <div class="fw-semibold">

                                            {{ $movimiento->fecha->format('d/m/Y') }}

                                        </div>

                                        <div class="small text-muted">

                                            {{ $movimiento->fecha->format('H:i') }}

                                        </div>

                                    </td>


                                    {{-- MEDICAMENTO --}}
                                    <td class="fw-semibold">

                                        @if(
                                            $movimiento->inventarioPaciente?->medicamento
                                        )

                                            {{ $movimiento->inventarioPaciente->medicamento->nombre }}

                                            @if(
                                                $movimiento->inventarioPaciente->medicamento->concentracion
                                            )

                                                <div class="small text-muted">

                                                    {{ $movimiento->inventarioPaciente->medicamento->concentracion }}

                                                </div>

                                            @endif

                                        @else

                                            —

                                        @endif

                                    </td>


                                    {{-- TIPO --}}
                                    <td>

                                        @if(
                                            $movimiento->tipo_movimiento === 'entrada'
                                        )

                                            <span
                                                class="badge rounded-pill text-bg-success"
                                            >
                                                Entrada
                                            </span>

                                        @elseif(
                                            $movimiento->tipo_movimiento === 'salida'
                                        )

                                            <span
                                                class="badge rounded-pill text-bg-danger"
                                            >
                                                Salida
                                            </span>

                                        @elseif(
                                            $movimiento->tipo_movimiento === 'transferencia'
                                        )

                                            <span
                                                class="badge rounded-pill text-bg-primary"
                                            >
                                                Transferencia
                                            </span>

                                        @else

                                            <span
                                                class="badge rounded-pill text-bg-secondary"
                                            >
                                                {{ ucfirst($movimiento->tipo_movimiento) }}
                                            </span>

                                        @endif

                                    </td>


                                    {{-- CANTIDAD --}}
                                    <td class="fw-bold">

                                        {{ $movimiento->cantidad }}

                                    </td>


                                    {{-- MOTIVO --}}
                                    <td>

                                        {{ $movimiento->motivo ?? '—' }}

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="5"
                                        class="text-center py-5 text-muted"
                                    >

                                        <i
                                            class="bi bi-clock-history fs-1 d-block mb-3"
                                        ></i>

                                        Este paciente todavía no tiene
                                        movimientos registrados.

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- MODAL REGISTRAR MEDICAMENTO EN PACIENTE --}}
<div
    class="modal fade"
    id="modalRegistrarMedicamentoPaciente"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content border-0 rounded-4 shadow">

            <form
                method="POST"
                action="{{ route('inventario.paciente.registrar') }}"
            >

                @csrf


                <input
                    type="hidden"
                    name="paciente_id"
                    value="{{ $paciente->id }}"
                >


                <div class="modal-header border-0">

                    <div>

                        <h5
                            class="modal-title fw-bold"
                            style="color:#0f172a;"
                        >
                            Registrar medicamento
                        </h5>

                        <small class="text-muted">

                            Inventario de
                            {{ $paciente->nombre }}
                            {{ $paciente->apellido }}

                        </small>

                    </div>


                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                    ></button>

                </div>


                <div class="modal-body">

                    {{-- MEDICAMENTO --}}
                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Medicamento
                        </label>

                        <select
                            name="medicamento_id"
                            class="form-select"
                            required
                        >

                            <option value="">
                                Selecciona un medicamento
                            </option>

                            @foreach($medicamentos as $medicamento)

                                <option value="{{ $medicamento->id }}">

                                    {{ $medicamento->nombre }}

                                    @if($medicamento->concentracion)

                                        - {{ $medicamento->concentracion }}

                                    @endif

                                    @if($medicamento->presentacion)

                                        · {{ $medicamento->presentacion }}

                                    @endif

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- CANTIDAD --}}
                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Cantidad
                        </label>

                        <input
                            type="number"
                            name="cantidad"
                            min="1"
                            class="form-control"
                            required
                        >

                    </div>


                    {{-- STOCK MÍNIMO --}}
                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Stock mínimo
                        </label>

                        <input
                            type="number"
                            name="cantidad_minima"
                            min="0"
                            value="0"
                            class="form-control"
                        >

                        <small class="text-muted">

                            Cantidad a partir de la cual se considera
                            necesario reponer.

                        </small>

                    </div>


                    {{-- FECHA DE VENCIMIENTO --}}
                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Fecha de vencimiento
                        </label>

                        <input
                            type="date"
                            name="fecha_vencimiento"
                            class="form-control"
                        >

                    </div>


                    {{-- LOTE --}}
                    <div>

                        <label class="form-label fw-semibold">
                            Lote
                        </label>

                        <input
                            type="text"
                            name="lote"
                            maxlength="100"
                            class="form-control"
                            placeholder="Ej. LOT-2026-001"
                        >

                    </div>

                </div>


                <div class="modal-footer border-0">

                    <button
                        type="button"
                        class="btn btn-light"
                        data-bs-dismiss="modal"
                    >
                        Cancelar
                    </button>


                    <button
                        type="submit"
                        class="btn btn-success"
                    >

                        <i class="bi bi-check-lg me-1"></i>

                        Registrar medicamento

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

</div>

@endsection
