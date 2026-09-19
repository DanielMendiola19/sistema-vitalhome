@extends('layouts.app')

@section('title', 'Inventario por paciente')

@section('content')

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>

            <h1
                class="fw-bold mb-1"
                style="color:#0f172a;"
            >
                Inventario por paciente
            </h1>

            <p class="text-muted mb-0">
                Elige un paciente para ver únicamente sus medicamentos.
            </p>

        </div>

    </div>


    {{-- TABS --}}

    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-body p-2">

            <div class="d-flex gap-2">

                <a
                    href="{{ route('inventario.index') }}"
                    class="btn btn-light rounded-3 px-4 py-3"
                >
                    <i class="bi bi-capsule me-2"></i>
                    Ver inventario general
                </a>

                <a
                    href="{{ route('inventario.pacientes') }}"
                    class="btn text-white rounded-3 px-4 py-3"
                    style="background:#0f2d6b;"
                >
                    <i class="bi bi-person me-2"></i>
                    Ver inventario por paciente
                </a>

            </div>

        </div>

    </div>


    {{-- BUSCADOR --}}

    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-body">

            <form
                method="GET"
                action="{{ route('inventario.pacientes') }}"
                id="formBuscarPacientesInventario"
            >

                <div class="input-group">

                    <span class="input-group-text bg-white">
                        <i class="bi bi-search"></i>
                    </span>

                    <input
                        type="text"
                        name="buscar"
                        class="form-control"
                        value="{{ $buscar }}"
                        placeholder="Buscar paciente por nombre o CI..."
                        autocomplete="off"
                        id="buscarPacientesInventario"
                    >

                    @if($buscar !== '')
                        <a
                            href="{{ route('inventario.pacientes') }}"
                            class="btn btn-outline-secondary"
                        >
                            <i class="bi bi-x-lg me-1"></i>
                            Limpiar
                        </a>
                    @endif

                </div>

            </form>

        </div>

    </div>


    {{-- GRID --}}

    <div class="row g-4">

        @forelse($pacientes as $paciente)

            @php

                $inventariosPaciente =
                    $paciente->inventariosPacientes;

                $cantidadMedicamentos =
                    $inventariosPaciente->count();

                $diagnosticos =
                    $paciente->tratamientos
                        ->pluck('medicamento.nombre')
                        ->filter()
                        ->unique()
                        ->take(2);

            @endphp

            <div class="col-md-6 col-xl-4">

                <div class="card border-0 shadow-sm rounded-4 h-100">

                    <div class="card-body p-4">

                        <div class="d-flex align-items-center mb-3">

                            <div
                                class="rounded-circle d-flex align-items-center justify-content-center me-3"
                                style="
                                    width:52px;
                                    height:52px;
                                    background:#eff6ff;
                                    color:#0f2d6b;
                                    font-weight:700;
                                    font-size:20px;
                                "
                            >
                                {{ $paciente->id }}
                            </div>

                            <div>

                                <h5
                                    class="fw-bold mb-1"
                                    style="color:#0f172a;"
                                >
                                    {{ $paciente->nombre }}
                                    {{ $paciente->apellido }}
                                </h5>

                                <div class="text-muted small">

                                    @if($paciente->fecha_nacimiento)

                                        {{ $paciente->fecha_nacimiento->age }}
                                        años

                                    @endif

                                    · Paciente

                                </div>

                            </div>

                        </div>


                        <div class="mb-3">

                            @forelse($diagnosticos as $diagnostico)

                                <span class="text-muted small">
                                    {{ $diagnostico }}
                                </span>

                                @if(!$loop->last)
                                    <span class="text-muted"> · </span>
                                @endif

                            @empty

                                <span class="text-muted small">
                                    Sin tratamientos registrados
                                </span>

                            @endforelse

                        </div>


                        <div
                            class="fw-semibold mb-4"
                            style="color:#059669;"
                        >
                            {{ $cantidadMedicamentos }}
                            medicamentos asignados
                        </div>


                        <a
                            href="{{ route(
                                'inventario.paciente',
                                $paciente
                            ) }}"
                            class="btn w-100 text-white"
                            style="background:#0f2d6b;"
                        >
                            Ver medicamentos
                        </a>

                    </div>

                </div>

            </div>

        @empty

            <div class="col-12">

                <div
                    class="card border-0 shadow-sm rounded-4"
                >

                    <div class="card-body text-center py-5">

                        <i class="bi bi-people fs-1 text-muted"></i>

                        <p class="text-muted mb-0 mt-3">
                            No se encontraron pacientes.
                        </p>

                    </div>

                </div>

            </div>

        @endforelse

    </div>


    @if($pacientes->total() > 0)
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mt-4">
            <div class="text-muted small">
                Mostrando {{ $pacientes->firstItem() }}–{{ $pacientes->lastItem() }}
                de {{ $pacientes->total() }} pacientes
            </div>

            @if($pacientes->hasPages())
                <nav aria-label="Paginación de pacientes">
                    <ul class="pagination mb-0">
                        <li class="page-item {{ $pacientes->onFirstPage() ? 'disabled' : '' }}">
                            @if($pacientes->onFirstPage())
                                <span class="page-link"><i class="bi bi-chevron-left"></i></span>
                            @else
                                <a class="page-link" href="{{ $pacientes->previousPageUrl() }}" aria-label="Página anterior">
                                    <i class="bi bi-chevron-left"></i>
                                </a>
                            @endif
                        </li>

                        @foreach($pacientes->getUrlRange(1, $pacientes->lastPage()) as $pagina => $url)
                            <li class="page-item {{ $pagina === $pacientes->currentPage() ? 'active' : '' }}">
                                <a class="page-link" href="{{ $url }}">{{ $pagina }}</a>
                            </li>
                        @endforeach

                        <li class="page-item {{ $pacientes->hasMorePages() ? '' : 'disabled' }}">
                            @if($pacientes->hasMorePages())
                                <a class="page-link" href="{{ $pacientes->nextPageUrl() }}" aria-label="Página siguiente">
                                    <i class="bi bi-chevron-right"></i>
                                </a>
                            @else
                                <span class="page-link"><i class="bi bi-chevron-right"></i></span>
                            @endif
                        </li>
                    </ul>
                </nav>
            @endif
        </div>
    @endif

    <script>
            document.addEventListener('DOMContentLoaded', function () {
                const input = document.getElementById('buscarPacientesInventario');
                const form = document.getElementById('formBuscarPacientesInventario');
                if (!input || !form) return;

                let timer = null;
                input.addEventListener('input', function () {
                    clearTimeout(timer);
                    timer = setTimeout(function () {
                        form.submit();
                    }, 450);
                });
            });
    </script>

</div>

@endsection
