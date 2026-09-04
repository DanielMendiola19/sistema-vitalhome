<div
    class="modal fade"
    id="modalTransferencia"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content border-0 rounded-4 shadow">

            <form
                method="POST"
                action="{{ route('inventario.transferir') }}"
            >

                @csrf

                <input
                    type="hidden"
                    name="inventario_id"
                    id="transferencia_inventario_id"
                >

                <div class="modal-header border-0">

                    <div>

                        <h5
                            class="modal-title fw-bold"
                            style="color:#0f172a;"
                        >
                            Transferir medicamento
                        </h5>

                        <small
                            class="text-muted"
                            id="transferencia_medicamento_nombre"
                        >
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
                            Paciente
                        </label>

                        <select
                            name="paciente_id"
                            class="form-select"
                            required
                        >

                            <option value="">
                                Selecciona un paciente
                            </option>

                            @foreach($pacientes as $paciente)

                                <option value="{{ $paciente->id }}">

                                    {{ $paciente->nombre }}
                                    {{ $paciente->apellido }}

                                </option>

                            @endforeach

                        </select>

                    </div>


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
                            value="Asignación al paciente"
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
                        <i class="bi bi-arrow-left-right me-1"></i>
                        Transferir
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>
