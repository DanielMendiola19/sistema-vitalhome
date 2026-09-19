<div
    class="modal fade"
    id="modalEntrada"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content border-0 rounded-4">

            <form
                method="POST"
                action="{{ route('inventario.entrada') }}"
            >

                @csrf

                <div class="modal-header border-0">

                    <div>

                        <h5 class="fw-bold mb-1">
                            Registrar entrada
                        </h5>

                        <small class="text-muted">
                            Agrega existencias a un medicamento registrado.
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

                        <label class="form-label fw-semibold">
                            Medicamento *
                        </label>

                        <div class="position-relative">
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="bi bi-search"></i>
                                </span>

                                <input
                                    type="text"
                                    id="buscador_medicamento_entrada"
                                    class="form-control"
                                    placeholder="Buscar por nombre, concentración o presentación..."
                                    autocomplete="off"
                                >
                            </div>

                            <input
                                type="hidden"
                                name="medicamento_id"
                                id="medicamento_id"
                                value="{{ old('medicamento_id') }}"
                                required
                            >

                            <div
                                id="resultados_medicamento_entrada"
                                class="list-group position-absolute w-100 shadow-sm d-none"
                                style="z-index:1060; max-height:260px; overflow-y:auto;"
                            ></div>
                        </div>

                        <div id="medicamento_entrada_seleccionado" class="small text-success fw-semibold mt-2 d-none"></div>
                        <div id="medicamento_entrada_error" class="small text-danger mt-1 d-none">
                            Selecciona un medicamento de la lista.
                        </div>

                        @error('medicamento_id')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror

                    </div>

                    <div class="mb-3">

                        <label
                            for="cantidad_entrada"
                            class="form-label"
                        >
                            Cantidad *
                        </label>

                        <input
                            type="number"
                            name="cantidad"
                            id="cantidad_entrada"
                            class="form-control"
                            min="1"
                            value="{{ old('cantidad') }}"
                            required
                        >

                        @error('cantidad')

                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    <div class="row g-3">

                        <div class="col-md-6">

                            <label
                                for="lote_entrada"
                                class="form-label"
                            >
                                Lote
                            </label>

                            <input
                                type="text"
                                name="lote"
                                id="lote_entrada"
                                class="form-control"
                                maxlength="100"
                                value="{{ old('lote') }}"
                                placeholder="Ej. LOT-2026-001"
                            >

                            @error('lote')

                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        <div class="col-md-6">

                            <label
                                for="fecha_vencimiento_entrada"
                                class="form-label"
                            >
                                Fecha de vencimiento
                            </label>

                            <input
                                type="date"
                                name="fecha_vencimiento"
                                id="fecha_vencimiento_entrada"
                                class="form-control"
                                value="{{ old('fecha_vencimiento') }}"
                            >

                            @error('fecha_vencimiento')

                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>


                    <div class="mt-3">

                        <label
                            for="motivo_entrada"
                            class="form-label"
                        >
                            Motivo
                        </label>

                        <input
                            type="text"
                            name="motivo"
                            id="motivo_entrada"
                            class="form-control"
                            maxlength="150"
                            value="{{ old('motivo', 'Compra') }}"
                            placeholder="Ej. Compra, donación, reposición..."
                        >

                        @error('motivo')

                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>

                        @enderror

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
                        <i class="bi bi-box-arrow-in-down me-1"></i>
                        Registrar entrada
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@php
    $medicamentosEntrada = $medicamentos->map(function ($medicamento) {
        return [
            'id' => $medicamento->id,
            'nombre' => $medicamento->nombre,
            'concentracion' => $medicamento->concentracion,
            'unidad' => $medicamento->unidad_medida ?? null,
            'presentacion' => $medicamento->presentacion,
        ];
    })->values()->all();
@endphp

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('modalEntrada');
    const buscador = document.getElementById('buscador_medicamento_entrada');
    const medicamentoId = document.getElementById('medicamento_id');
    const resultados = document.getElementById('resultados_medicamento_entrada');
    const seleccionado = document.getElementById('medicamento_entrada_seleccionado');
    const error = document.getElementById('medicamento_entrada_error');

    if (!modal || !buscador || !medicamentoId || !resultados) return;

    const medicamentos = @json($medicamentosEntrada);

    const textoMedicamento = (m) => {
        const concentracion = [m.concentracion, m.unidad].filter(Boolean).join(' ');
        return [m.nombre, concentracion, m.presentacion].filter(Boolean).join(' · ');
    };

    const normalizar = (valor) => (valor || '').toString().toLocaleLowerCase('es').normalize('NFD').replace(/[\u0300-\u036f]/g, '');

    const ocultarResultados = () => {
        resultados.classList.add('d-none');
        resultados.innerHTML = '';
    };

    const seleccionar = (m) => {
        const texto = textoMedicamento(m);
        medicamentoId.value = m.id;
        buscador.value = texto;
        seleccionado.textContent = 'Seleccionado: ' + texto;
        seleccionado.classList.remove('d-none');
        error.classList.add('d-none');
        ocultarResultados();
    };

    const renderizar = () => {
        const termino = normalizar(buscador.value.trim());
        medicamentoId.value = '';
        seleccionado.classList.add('d-none');

        if (!termino) {
            ocultarResultados();
            return;
        }

        const coincidencias = medicamentos.filter((m) =>
            normalizar(textoMedicamento(m)).includes(termino)
        ).slice(0, 20);

        resultados.innerHTML = '';

        if (!coincidencias.length) {
            const vacio = document.createElement('div');
            vacio.className = 'list-group-item text-muted small';
            vacio.textContent = 'No se encontraron medicamentos.';
            resultados.appendChild(vacio);
        } else {
            coincidencias.forEach((m) => {
                const boton = document.createElement('button');
                boton.type = 'button';
                boton.className = 'list-group-item list-group-item-action';

                const nombre = document.createElement('div');
                nombre.className = 'fw-semibold';
                nombre.textContent = m.nombre;

                const detalle = document.createElement('div');
                detalle.className = 'small text-muted';
                detalle.textContent = [
                    [m.concentracion, m.unidad].filter(Boolean).join(' '),
                    m.presentacion
                ].filter(Boolean).join(' · ') || 'Sin detalles adicionales';

                boton.append(nombre, detalle);
                boton.addEventListener('click', () => seleccionar(m));
                resultados.appendChild(boton);
            });
        }

        resultados.classList.remove('d-none');
    };

    buscador.addEventListener('input', renderizar);
    buscador.addEventListener('focus', function () {
        if (buscador.value.trim() && !medicamentoId.value) renderizar();
    });

    modal.querySelector('form').addEventListener('submit', function (event) {
        if (!medicamentoId.value) {
            event.preventDefault();
            error.classList.remove('d-none');
            buscador.focus();
        }
    });

    document.addEventListener('click', function (event) {
        if (!resultados.contains(event.target) && event.target !== buscador) ocultarResultados();
    });

    const anterior = medicamentos.find((m) => String(m.id) === String(medicamentoId.value));
    if (anterior) seleccionar(anterior);
});
</script>

