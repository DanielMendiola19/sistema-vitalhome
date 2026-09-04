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
                        placeholder="Buscar paciente por nombre o carnet..."
                    >

                    <button
                        type="submit"
                        class="btn btn-success"
                    >
                        Buscar
                    </button>

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


    <div class="mt-4">

        {{ $pacientes->links() }}

    </div>

</div>

@endsection
