<div class="card-header bg-white border-0 pt-4 px-4">

    <div class="d-flex justify-content-between align-items-start">

        <div>

            <div class="text-muted small mb-1">
                Detalle del medicamento
            </div>

            <h4
                class="fw-bold mb-1"
                style="color:#0f172a;"
            >
                {{ $detalle->medicamento->nombre }}
            </h4>

            @if($detalle->medicamento->concentracion)

                <div class="text-muted">
                    {{ $detalle->medicamento->concentracion }}
                </div>

            @endif

        </div>

        <a
            href="{{ route('inventario.index', request()->except('medicamento')) }}"
            class="btn btn-sm btn-light rounded-circle"
            title="Cerrar"
        >
            <i class="bi bi-x-lg"></i>
        </a>

    </div>

</div>


<div class="card-body px-4">

    @php

        if ($detalle->cantidad_actual <= 0) {
            $estado = 'Reposición necesaria';
            $clase = 'danger';
        } elseif (
            $detalle->cantidad_actual <=
            $detalle->cantidad_minima
        ) {
            $estado = 'Stock bajo';
            $clase = 'warning';
        } else {
            $estado = 'Disponible';
            $clase = 'success';
        }

    @endphp

    <div class="mb-4">

        <span class="badge rounded-pill text-bg-{{ $clase }}">

            <i class="bi bi-circle-fill me-1"
               style="font-size:7px;">
            </i>

            {{ $estado }}

        </span>

    </div>


    <div class="list-group list-group-flush">

        <div class="list-group-item px-0 d-flex justify-content-between">
            <span class="text-muted">
                Presentación
            </span>

            <strong>
                {{ $detalle->medicamento->presentacion }}
            </strong>
        </div>

        <div class="list-group-item px-0 d-flex justify-content-between">
            <span class="text-muted">
                Stock actual
            </span>

            <strong>
                {{ $detalle->cantidad_actual }}
            </strong>
        </div>

        <div class="list-group-item px-0 d-flex justify-content-between">
            <span class="text-muted">
                Stock mínimo
            </span>

            <strong>
                {{ $detalle->cantidad_minima }}
            </strong>
        </div>

        <div class="list-group-item px-0 d-flex justify-content-between">
            <span class="text-muted">
                Vencimiento
            </span>

            <strong>

                @if($detalle->fecha_vencimiento)

                    {{ $detalle->fecha_vencimiento->format('m/Y') }}

                @else

                    —

                @endif

            </strong>
        </div>

        <div class="list-group-item px-0 d-flex justify-content-between">
            <span class="text-muted">
                Lote
            </span>

            <strong>
                {{ $detalle->lote ?: '—' }}
            </strong>
        </div>

        <div class="list-group-item px-0 d-flex justify-content-between">
            <span class="text-muted">
                Tipo
            </span>

            <strong>
                {{ $detalle->medicamento->principio_activo ?: '—' }}
            </strong>
        </div>

    </div>


    {{-- ACCIONES --}}

    <div class="d-grid gap-2 mt-4">

        <button
            type="button"
            class="btn btn-success"
            data-bs-toggle="modal"
            data-bs-target="#modalTransferencia"
            data-inventario-id="{{ $detalle->id }}"
            data-medicamento="{{ $detalle->medicamento->nombre }}"
        >
            <i class="bi bi-arrow-left-right me-2"></i>
            Transferir a paciente
        </button>

        <button
            type="button"
            class="btn btn-outline-secondary"
            data-bs-toggle="modal"
            data-bs-target="#modalSalida"
            data-inventario-id="{{ $detalle->id }}"
        >
            <i class="bi bi-box-arrow-up me-2"></i>
            Registrar salida
        </button>

    </div>


    {{-- HISTORIAL --}}

    <hr class="my-4">

    <h6
        class="fw-bold mb-3"
        style="color:#0f172a;"
    >
        Últimos movimientos
    </h6>

    @forelse($detalle->movimientos as $movimiento)

        <div class="border-bottom py-2">

            <div class="d-flex justify-content-between">

                <strong class="small">
                    {{ ucfirst($movimiento->tipo_movimiento) }}
                </strong>

                <span class="small text-muted">
                    {{ $movimiento->fecha->format('d/m/Y H:i') }}
                </span>

            </div>

            <div class="small text-muted">

                {{ $movimiento->cantidad }}
                unidades

                @if($movimiento->paciente)

                    · {{ $movimiento->paciente->nombre }}
                    {{ $movimiento->paciente->apellido }}

                @endif

            </div>

        </div>

    @empty

        <p class="text-muted small mb-0">
            Todavía no existen movimientos registrados.
        </p>

    @endforelse


</div>
