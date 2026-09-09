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

                        <label
                            for="medicamento_id"
                            class="form-label"
                        >
                            Medicamento *
                        </label>

                        <select
                            name="medicamento_id"
                            id="medicamento_id"
                            class="form-select"
                            required
                        >

                            <option value="">
                                Seleccionar medicamento
                            </option>

                            @foreach($medicamentos as $medicamento)

                                <option
                                    value="{{ $medicamento->id }}"
                                    {{ old('medicamento_id') == $medicamento->id ? 'selected' : '' }}
                                >

                                    {{ $medicamento->nombre }}

                                    @if($medicamento->concentracion)
                                        — {{ $medicamento->concentracion }}
                                    @endif

                                    @if($medicamento->presentacion)
                                        ({{ $medicamento->presentacion }})
                                    @endif

                                </option>

                            @endforeach

                        </select>

                        @error('medicamento_id')

                            <div class="text-danger small mt-1">
                                {{ $message }}
                            </div>

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
