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

        <a
            href="#"
            class="btn btn-outline-secondary"
        >
            <i class="bi bi-exclamation-triangle me-2"></i>
            Ver alertas
        </a>

    </div>

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

    <div class="row g-4">

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

                        <a
                            href="#"
                            class="text-decoration-none"
                            style="color:#388E3C;"
                        >
                            Ver inventario →
                        </a>

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
                                            {{ $inventario->medicamento->nombre }}
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

                                            @if ($inventario->cantidad_actual <= $inventario->cantidad_minima)

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

                        <div class="d-flex mb-4">

                            <div class="me-3 text-secondary">
                                <i class="bi bi-file-earmark-text fs-5"></i>
                            </div>

                            <div>

                                <div class="small">
                                    Sistema iniciado correctamente
                                </div>

                                <small class="text-secondary-vital">
                                    Ahora
                                </small>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<script>
    const rememberLogin = @json(
        session('remember_login', Auth::viaRemember())
    );

    const inactivityTimeout = rememberLogin
        ? 8 * 60 * 1000
        : 2 * 60 * 1000;

    let inactivityTimer;

    const resetInactivityTimer = () => {
        clearTimeout(inactivityTimer);

        inactivityTimer = setTimeout(
            logoutByInactivity,
            inactivityTimeout
        );
    };

    const logoutByInactivity = () => {
        fetch('{{ route('logout') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        }).finally(() => {
            window.location.href = '{{ route('login') }}?expired=1';
        });
    };

    [
        'mousemove',
        'keydown',
        'click',
        'scroll',
        'touchstart'
    ].forEach(event => {
        document.addEventListener(event, resetInactivityTimer);
    });

    resetInactivityTimer();
</script>

@endsection
