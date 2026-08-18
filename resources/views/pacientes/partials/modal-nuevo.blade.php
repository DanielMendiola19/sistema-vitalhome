<div
    class="modal fade"
    id="nuevoPacienteModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content border-0 rounded-4 shadow">

            <div class="modal-header">

                <div>

                    <h5 class="modal-title fw-bold">
                        Nuevo paciente
                    </h5>

                    <small class="text-muted">
                        Registra la información del paciente
                    </small>

                </div>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                ></button>

            </div>


            <form
                method="POST"
                action="{{ route('pacientes.store') }}"
            >

                @csrf

                <div class="modal-body">

                    <div class="row g-3">

                        {{-- NOMBRE --}}

                        <div class="col-md-6">

                            <label class="form-label">
                                Nombre
                            </label>

                            <input
                                type="text"
                                name="nombre"
                                class="form-control"
                                required
                            >

                        </div>


                        {{-- APELLIDO --}}

                        <div class="col-md-6">

                            <label class="form-label">
                                Apellido
                            </label>

                            <input
                                type="text"
                                name="apellido"
                                class="form-control"
                                required
                            >

                        </div>


                        {{-- EDAD --}}

                        <div class="col-md-4">

                            <label class="form-label">
                                Edad
                            </label>

                            <input
                                type="number"
                                name="edad"
                                class="form-control"
                                min="0"
                                max="120"
                                required
                            >

                        </div>


                        {{-- SEXO --}}

                        <div class="col-md-4">

                            <label class="form-label">
                                Sexo
                            </label>

                            <select
                                name="sexo"
                                class="form-select"
                                required
                            >

                                <option value="">
                                    Seleccionar
                                </option>

                                <option value="Femenino">
                                    Femenino
                                </option>

                                <option value="Masculino">
                                    Masculino
                                </option>

                            </select>

                        </div>


                        {{-- ESTADO --}}

                        <div class="col-md-4">

                            <label class="form-label">
                                Estado
                            </label>

                            <select
                                name="estado"
                                class="form-select"
                                required
                            >

                                <option value="Activo">
                                    Activo
                                </option>

                                <option value="Inactivo">
                                    Inactivo
                                </option>

                            </select>

                        </div>


                        {{-- DIAGNÓSTICO --}}

                        <div class="col-md-12">

                            <label class="form-label">
                                Diagnóstico
                            </label>

                            <input
                                type="text"
                                name="diagnostico"
                                class="form-control"
                            >

                        </div>


                        {{-- FECHA INGRESO --}}

                        <div class="col-md-6">

                            <label class="form-label">
                                Fecha de ingreso
                            </label>

                            <input
                                type="date"
                                name="fecha_ingreso"
                                class="form-control"
                                required
                            >

                        </div>


                        {{-- CÓDIGO --}}

                        <div class="col-md-6">

                            <label class="form-label">
                                Código interno
                            </label>

                            <input
                                type="text"
                                name="codigo_interno"
                                class="form-control"
                                placeholder="Ej. P001"
                                required
                            >

                        </div>

                    </div>

                </div>


                <div class="modal-footer">

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

                        <i class="bi bi-check-lg"></i>

                        Guardar paciente

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>
