<div
    class="modal fade"
    id="modalSalida"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content border-0 rounded-4 shadow">

            <form
                method="POST"
                action="{{ route('inventario.salida') }}"
            >

                @csrf

                <input
                    type="hidden"
                    name="inventario_id"
                    id="salida_inventario_id"
                    value=""
                >

                <div class="modal-header border-0">

                    <div>

                        <h5
                            class="modal-title fw-bold"
                            style="color:#0f172a;"
                        >
                            Registrar salida
                        </h5>

                        <small class="text-muted">
                            Descuenta unidades del inventario general.
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
                            Cantidad
                        </label>

                        <input
                            type="number"
                            name="cantidad"
                            min="1"
                            class="form-control"
                            required
                        >

                    </div>


                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Motivo
                        </label>

                        <input
                            type="text"
                            name="motivo"
                            maxlength="150"
                            class="form-control"
                            placeholder="Ej. Baja, daño, consumo..."
                        >

                    </div>


                    <div>

                        <label class="form-label fw-semibold">
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
