<div
    class="modal fade"
    id="modalEntrada"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content border-0 rounded-4 shadow">

            <form
                method="POST"
                action="{{ route('inventario.entrada') }}"
            >

                @csrf

                <div class="modal-header border-0">

                    <div>

                        <h5
                            class="modal-title fw-bold"
                            style="color:#0f172a;"
                        >
                            Registrar entrada
                        </h5>

                        <small class="text-muted">
                            Agrega unidades al inventario general.
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

                                <option
                                    value="{{ $medicamento->id }}"
                                    @selected(
                                        old('medicamento_id') ==
                                        $medicamento->id
                                    )
                                >
                                    {{ $medicamento->nombre }}

                                    @if($medicamento->concentracion)
                                        - {{ $medicamento->concentracion }}
                                    @endif
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div class="row g-3">

                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Cantidad
                            </label>

                            <input
                                type="number"
                                name="cantidad"
                                min="1"
                                class="form-control"
                                value="{{ old('cantidad') }}"
                                required
                            >

                        </div>

                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Lote
                            </label>

                            <input
                                type="text"
                                name="lote"
                                class="form-control"
                                value="{{ old('lote') }}"
                            >

                        </div>

                    </div>


                    <div class="mt-3">

                        <label class="form-label fw-semibold">
                            Fecha de vencimiento
                        </label>

                        <input
                            type="date"
                            name="fecha_vencimiento"
                            class="form-control"
                            value="{{ old('fecha_vencimiento') }}"
                        >

                    </div>


                    <div class="mt-3">

                        <label class="form-label fw-semibold">
                            Motivo
                        </label>

                        <input
                            type="text"
                            name="motivo"
                            class="form-control"
                            maxlength="150"
                            placeholder="Ej. Compra, reposición..."
                            value="{{ old('motivo') }}"
                        >

                    </div>


                    <div class="mt-3">

                        <label class="form-label fw-semibold">
                            Observaciones
                        </label>

                        <textarea
                            name="observaciones"
                            class="form-control"
                            rows="3"
                        >{{ old('observaciones') }}</textarea>

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
                        Registrar entrada
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>
