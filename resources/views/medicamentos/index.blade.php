@extends('layouts.app')

@section('title', 'Medicamentos')

@section('content')

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-start mb-4">

        <div>
            <h1
                class="fw-bold mb-1"
                style="color:#0f172a;"
            >
                Medicamentos
            </h1>

            <p class="text-muted mb-0">
                Catálogo de medicamentos registrados en VITALHOME.
            </p>
        </div>

        <a
            href="{{ route('medicamentos.create') }}"
            class="btn btn-success"
        >
            <i class="bi bi-plus-lg me-1"></i>
            Registrar medicamento
        </a>

    </div>


    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>
        </div>

    @endif


    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-body">

            <form
                method="GET"
                action="{{ route('medicamentos.index') }}"
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
                        placeholder="Buscar medicamento..."
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


    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table align-middle">

                    <thead>

                        <tr class="small text-muted">

                            <th>MEDICAMENTO</th>
                            <th>PRINCIPIO ACTIVO</th>
                            <th>PRESENTACIÓN</th>
                            <th>CONCENTRACIÓN</th>
                            <th>UNIDAD</th>
                            <th>ESTADO</th>
                            <th>ACCIÓN</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($medicamentos as $medicamento)

                            <tr>

                                <td class="fw-semibold">

                                    {{ $medicamento->nombre }}

                                </td>

                                <td>
                                    {{ $medicamento->principio_activo ?? '—' }}
                                </td>

                                <td>
                                    {{ $medicamento->presentacion }}
                                </td>

                                <td>
                                    {{ $medicamento->concentracion ?? '—' }}
                                </td>

                                <td>
                                    {{ $medicamento->unidad_medida ?? '—' }}
                                </td>

                                <td>

                                    @if($medicamento->activo)

                                        <span class="badge rounded-pill text-bg-success">
                                            Activo
                                        </span>

                                    @else

                                        <span class="badge rounded-pill text-bg-secondary">
                                            Inactivo
                                        </span>

                                    @endif

                                </td>

                                <td>

                                    <a
                                        href="{{ route(
                                            'medicamentos.edit',
                                            $medicamento
                                        ) }}"
                                        class="btn btn-sm btn-link text-success text-decoration-none fw-semibold"
                                    >
                                        Editar
                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="7"
                                    class="text-center py-5 text-muted"
                                >

                                    <i class="bi bi-capsule fs-1 d-block mb-3"></i>

                                    No hay medicamentos registrados.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    <div class="mt-4">

        {{ $medicamentos->links() }}

    </div>

</div>

@endsection
