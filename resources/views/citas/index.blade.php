@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- ENCABEZADO --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">

        <div>

            <h2 class="fw-bold text-primary-vital mb-1">
                Gestión de Citas
            </h2>

            <p class="text-secondary-vital mb-0">
                Administra las citas y recojos de medicamentos de los pacientes.
            </p>

        </div>


        <a
            href="{{ route('citas.create') }}"
            class="btn btn-success"
        >
            <i class="bi bi-calendar-plus me-2"></i>
            Nueva cita
        </a>

    </div>


    {{-- ========================================================= --}}
    {{-- MENSAJES --}}
    {{-- ========================================================= --}}

    @if(session('success'))

        <div
            class="alert alert-success alert-dismissible fade show"
            role="alert"
        >

            <i class="bi bi-check-circle-fill me-2"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    @if(session('error'))

        <div
            class="alert alert-danger alert-dismissible fade show"
            role="alert"
        >

            <i class="bi bi-exclamation-circle-fill me-2"></i>

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- FILTROS --}}
    {{-- ========================================================= --}}

    <div class="card section-card mb-4">

        <div class="card-body">

            <div class="d-flex align-items-center mb-3">

                <div>

                    <h5 class="fw-bold mb-1">
                        <i class="bi bi-funnel me-2"></i>
                        Filtros
                    </h5>

                    <small class="text-secondary-vital">
                        Busca citas por fecha, tipo o estado.
                    </small>

                </div>

            </div>


            <form
                action="{{ route('citas.index') }}"
                method="GET"
            >

                <div class="row g-3 align-items-end">


                    {{-- FECHA --}}

                    <div class="col-12 col-md-4">

                        <label
                            for="fecha"
                            class="form-label fw-semibold"
                        >
                            Fecha
                        </label>

                        <input
                            type="date"
                            id="fecha"
                            name="fecha"
                            value="{{ request('fecha') }}"
                            class="form-control"
                        >

                    </div>


                    {{-- TIPO --}}

                    <div class="col-12 col-md-3">

                        <label
                            for="tipo"
                            class="form-label fw-semibold"
                        >
                            Tipo
                        </label>

                        <select
                            name="tipo"
                            id="tipo"
                            class="form-select"
                        >

                            <option value="">
                                Todos
                            </option>

                            <option
                                value="asistir_cita"
                                {{ request('tipo') === 'asistir_cita' ? 'selected' : '' }}
                            >
                                Asistir a cita
                            </option>

                            <option
                                value="recoger_medicamentos"
                                {{ request('tipo') === 'recoger_medicamentos' ? 'selected' : '' }}
                            >
                                Recoger medicamentos
                            </option>

                        </select>

                    </div>


                    {{-- ESTADO --}}

                    <div class="col-12 col-md-3">

                        <label
                            for="estado"
                            class="form-label fw-semibold"
                        >
                            Estado
                        </label>

                        <select
                            name="estado"
                            id="estado"
                            class="form-select"
                        >

                            <option value="">
                                Todos
                            </option>

                            <option
                                value="pendiente"
                                {{ request('estado') === 'pendiente' ? 'selected' : '' }}
                            >
                                Pendiente
                            </option>

                            <option
                                value="completada"
                                {{ request('estado') === 'completada' ? 'selected' : '' }}
                            >
                                Completada
                            </option>

                            <option
                                value="cancelada"
                                {{ request('estado') === 'cancelada' ? 'selected' : '' }}
                            >
                                Cancelada
                            </option>

                        </select>

                    </div>


                    {{-- BOTONES --}}

                    <div class="col-12 col-md-2">

                        <div class="d-flex gap-2">

                            <button
                                type="submit"
                                class="btn btn-success flex-fill"
                                title="Filtrar"
                            >
                                <i class="bi bi-search"></i>
                            </button>


                            <a
                                href="{{ route('citas.index') }}"
                                class="btn btn-outline-secondary flex-fill"
                                title="Limpiar filtros"
                            >
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </a>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- RESUMEN --}}
    {{-- ========================================================= --}}

    @php

        $totalCitas = $citas->count();

        $totalPendientes = $citas
            ->where('estado', 'pendiente')
            ->count();

        $totalCompletadas = $citas
            ->where('estado', 'completada')
            ->count();

        $totalCanceladas = $citas
            ->where('estado', 'cancelada')
            ->count();

    @endphp


    <div class="row g-3 mb-4">


        {{-- TOTAL --}}

        <div class="col-12 col-sm-6 col-xl-3">

            <div class="card section-card h-100">

                <div class="card-body">

                    <div class="d-flex align-items-center">

                        <div
                            class="d-flex align-items-center justify-content-center rounded-circle me-3"
                            style="
                                width:50px;
                                height:50px;
                                background:#E8F5E9;
                                color:#388E3C;
                            "
                        >

                            <i class="bi bi-calendar3 fs-4"></i>

                        </div>

                        <div>

                            <small class="text-secondary-vital">
                                Total
                            </small>

                            <h4 class="fw-bold mb-0">
                                {{ $totalCitas }}
                            </h4>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- PENDIENTES --}}

        <div class="col-12 col-sm-6 col-xl-3">

            <div class="card section-card h-100">

                <div class="card-body">

                    <div class="d-flex align-items-center">

                        <div
                            class="d-flex align-items-center justify-content-center rounded-circle me-3"
                            style="
                                width:50px;
                                height:50px;
                                background:#FFF8E1;
                                color:#FF9800;
                            "
                        >

                            <i class="bi bi-clock-history fs-4"></i>

                        </div>

                        <div>

                            <small class="text-secondary-vital">
                                Pendientes
                            </small>

                            <h4 class="fw-bold mb-0">
                                {{ $totalPendientes }}
                            </h4>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- COMPLETADAS --}}

        <div class="col-12 col-sm-6 col-xl-3">

            <div class="card section-card h-100">

                <div class="card-body">

                    <div class="d-flex align-items-center">

                        <div
                            class="d-flex align-items-center justify-content-center rounded-circle me-3"
                            style="
                                width:50px;
                                height:50px;
                                background:#E8F5E9;
                                color:#2E7D32;
                            "
                        >

                            <i class="bi bi-check-circle fs-4"></i>

                        </div>

                        <div>

                            <small class="text-secondary-vital">
                                Completadas
                            </small>

                            <h4 class="fw-bold mb-0">
                                {{ $totalCompletadas }}
                            </h4>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- CANCELADAS --}}

        <div class="col-12 col-sm-6 col-xl-3">

            <div class="card section-card h-100">

                <div class="card-body">

                    <div class="d-flex align-items-center">

                        <div
                            class="d-flex align-items-center justify-content-center rounded-circle me-3"
                            style="
                                width:50px;
                                height:50px;
                                background:#FFEBEE;
                                color:#D32F2F;
                            "
                        >

                            <i class="bi bi-x-circle fs-4"></i>

                        </div>

                        <div>

                            <small class="text-secondary-vital">
                                Canceladas
                            </small>

                            <h4 class="fw-bold mb-0">
                                {{ $totalCanceladas }}
                            </h4>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- TABLA DE CITAS --}}
    {{-- ========================================================= --}}

    <div class="card section-card">

        <div class="card-body">


            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">

                <div>

                    <h5 class="fw-bold mb-1">
                        Citas registradas
                    </h5>

                    <small class="text-secondary-vital">
                        {{ $totalCitas }}
                        {{ $totalCitas === 1 ? 'registro encontrado' : 'registros encontrados' }}
                    </small>

                </div>

            </div>


            <div class="table-responsive">

                <table class="table align-middle">

                    <thead class="table-light">

                        <tr>

                            <th>
                                PACIENTE
                            </th>

                            <th>
                                TIPO
                            </th>

                            <th>
                                FECHA
                            </th>

                            <th>
                                HORA
                            </th>

                            <th>
                                ESTADO
                            </th>

                            <th>
                                OBSERVACIONES
                            </th>

                            <th class="text-center">
                                ACCIONES
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($citas as $cita)

                            <tr>


                                {{-- PACIENTE --}}

                                <td>

                                    <div class="fw-semibold">

                                        {{ $cita->paciente?->nombre }}
                                        {{ $cita->paciente?->apellido }}

                                    </div>

                                    @if($cita->paciente?->ci)

                                        <small class="text-secondary-vital">

                                            CI:
                                            {{ $cita->paciente->ci }}

                                        </small>

                                    @endif

                                </td>


                                {{-- TIPO --}}

                                <td>

                                    @if($cita->tipo === 'recoger_medicamentos')

                                        <span
                                            class="badge rounded-pill"
                                            style="
                                                background:#E8F5E9;
                                                color:#2E7D32;
                                            "
                                        >

                                            <i class="bi bi-capsule me-1"></i>

                                            Recoger medicamentos

                                        </span>

                                    @elseif($cita->tipo === 'asistir_cita')

                                        <span
                                            class="badge rounded-pill"
                                            style="
                                                background:#E3F2FD;
                                                color:#1565C0;
                                            "
                                        >

                                            <i class="bi bi-person-check me-1"></i>

                                            Asistir a cita

                                        </span>

                                    @else

                                        <span class="badge bg-secondary">
                                            {{ ucfirst($cita->tipo) }}
                                        </span>

                                    @endif

                                </td>


                                {{-- FECHA --}}

                                <td>

                                    <div class="fw-semibold">

                                        {{ $cita->fecha?->format('d/m/Y') }}

                                    </div>

                                    @if(
                                        $cita->estado === 'pendiente' &&
                                        $cita->fecha?->isToday()
                                    )

                                        <small class="text-success fw-semibold">
                                            Hoy
                                        </small>

                                    @endif

                                </td>


                                {{-- HORA --}}

                                <td>

                                    <i class="bi bi-clock me-1 text-secondary"></i>

                                    {{
                                        \Carbon\Carbon::parse($cita->hora)
                                            ->format('H:i')
                                    }}

                                </td>


                                {{-- ESTADO --}}

                                <td>

                                    @if($cita->estado === 'pendiente')

                                        <span
                                            class="badge rounded-pill"
                                            style="
                                                background:#FFF8E1;
                                                color:#FF9800;
                                            "
                                        >
                                            <i class="bi bi-clock me-1"></i>
                                            Pendiente
                                        </span>

                                    @elseif($cita->estado === 'completada')

                                        <span
                                            class="badge rounded-pill"
                                            style="
                                                background:#E8F5E9;
                                                color:#2E7D32;
                                            "
                                        >
                                            <i class="bi bi-check-circle me-1"></i>
                                            Completada
                                        </span>

                                    @elseif($cita->estado === 'cancelada')

                                        <span
                                            class="badge rounded-pill"
                                            style="
                                                background:#FFEBEE;
                                                color:#D32F2F;
                                            "
                                        >
                                            <i class="bi bi-x-circle me-1"></i>
                                            Cancelada
                                        </span>

                                    @endif

                                </td>


                                {{-- OBSERVACIONES --}}

                                <td>

                                    @if($cita->observaciones)

                                        <span
                                            title="{{ $cita->observaciones }}"
                                        >

                                            {{
                                                \Illuminate\Support\Str::limit(
                                                    $cita->observaciones,
                                                    45
                                                )
                                            }}

                                        </span>

                                    @else

                                        <span class="text-secondary">
                                            —
                                        </span>

                                    @endif

                                </td>


                                {{-- ACCIONES --}}

                                <td>

                                    <div class="d-flex justify-content-center gap-2 flex-wrap">


                                        {{-- EDITAR --}}

                                        @if($cita->estado === 'pendiente')

                                            <a
                                                href="{{ route('citas.edit', $cita) }}"
                                                class="btn btn-sm btn-outline-primary"
                                                title="Editar cita"
                                            >
                                                <i class="bi bi-pencil-square"></i>
                                            </a>

                                        @endif


                                        {{-- COMPLETAR --}}

                                        @if($cita->estado === 'pendiente')

                                            <form
                                                action="{{ route('citas.completar', $cita) }}"
                                                method="POST"
                                                class="d-inline"
                                            >

                                                @csrf
                                                @method('PATCH')

                                                <button
                                                    type="submit"
                                                    class="btn btn-sm btn-outline-success"
                                                    title="Marcar como completada"
                                                    onclick="return confirm('¿Deseas marcar esta cita como completada?')"
                                                >
                                                    <i class="bi bi-check-lg"></i>
                                                </button>

                                            </form>

                                        @endif


                                        {{-- CANCELAR --}}

                                        @if($cita->estado === 'pendiente')

                                            <form
                                                action="{{ route('citas.cancelar', $cita) }}"
                                                method="POST"
                                                class="d-inline"
                                            >

                                                @csrf
                                                @method('PATCH')

                                                <button
                                                    type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    title="Cancelar cita"
                                                    onclick="return confirm('¿Deseas cancelar esta cita?')"
                                                >
                                                    <i class="bi bi-x-lg"></i>
                                                </button>

                                            </form>

                                        @endif


                                        {{-- REACTIVAR --}}

                                        @if(
                                            $cita->estado === 'cancelada' ||
                                            $cita->estado === 'completada'
                                        )

                                            <form
                                                action="{{ route('citas.reactivar', $cita) }}"
                                                method="POST"
                                                class="d-inline"
                                            >

                                                @csrf
                                                @method('PATCH')

                                                <button
                                                    type="submit"
                                                    class="btn btn-sm btn-outline-secondary"
                                                    title="Volver a pendiente"
                                                    onclick="return confirm('¿Deseas volver esta cita al estado pendiente?')"
                                                >
                                                    <i class="bi bi-arrow-counterclockwise"></i>
                                                </button>

                                            </form>

                                        @endif

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="7"
                                    class="text-center py-5"
                                >

                                    <i
                                        class="bi bi-calendar-x d-block mb-3 text-secondary"
                                        style="font-size:3rem;"
                                    ></i>

                                    <h6 class="fw-bold">
                                        No hay citas registradas
                                    </h6>

                                    <p class="text-secondary-vital mb-3">
                                        No se encontraron citas con los filtros seleccionados.
                                    </p>

                                    <a
                                        href="{{ route('citas.create') }}"
                                        class="btn btn-success"
                                    >
                                        <i class="bi bi-calendar-plus me-1"></i>
                                        Registrar primera cita
                                    </a>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection
