@extends('layouts.app')

@section('title', 'Inventario del paciente')

@section('content')

<div class="container-fluid py-4">

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

    </div>


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
                                            $estado = 'Reposición necesaria';
                                            $clase = 'danger';
                                        } elseif (
                                            $inventario->cantidad_actual
                                            <=
                                            $inventario->cantidad_minima
                                        ) {
                                            $estado = 'Stock bajo';
                                            $clase = 'warning';
                                        } else {
                                            $estado = 'Disponible';
                                            $clase = 'success';
                                        }

                                    @endphp

                                    <tr>

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

                                        <td>

                                            {{ $tratamiento?->dosis ?? '—' }}

                                        </td>

                                        <td>

                                            {{ $tratamiento?->frecuencia ?? '—' }}

                                        </td>

                                        <td>

                                            {{ $tratamiento?->via_administracion ?? '—' }}

                                        </td>

                                        <td class="fw-bold">

                                            {{ $inventario->cantidad_actual }}

                                        </td>

                                        <td>

                                            @if($consumoDiario)
                                                {{ $consumoDiario }} /día
                                            @else
                                                —
                                            @endif

                                        </td>

                                        <td>

                                            @if($diasRestantes !== null)
                                                {{ $diasRestantes }} días
                                            @else
                                                —
                                            @endif

                                        </td>

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

                                        <td>

                                            <span
                                                class="badge rounded-pill text-bg-{{ $clase }}"
                                            >
                                                {{ $estado }}
                                            </span>

                                        </td>

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
                                    >

                                        <div class="modal-dialog modal-dialog-centered">

                                            <div class="modal-content border-0 rounded-4">

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
                                                            </small>

                                                        </div>

                                                        <button
                                                            type="button"
                                                            class="btn-close"
                                                            data-bs-dismiss="modal"
                                                        ></button>

                                                    </div>

                                                    <div class="modal-body">

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

                                                        <div class="mb-3">

                                                            <label class="form-label">
                                                                Motivo
                                                            </label>

                                                            <input
                                                                type="text"
                                                                name="motivo"
                                                                class="form-control"
                                                                value="Administración"
                                                            >

                                                        </div>

                                                        <div>

                                                            <label class="form-label">
                                                                Observaciones
                                                            </label>

                                                            <textarea
                                                                name="observaciones"
                                                                class="form-control"
                                                                rows="3"
                                                            ></textarea>

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

                                            <i class="bi bi-capsule fs-1 d-block mb-3"></i>

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

</div>

@endsection
