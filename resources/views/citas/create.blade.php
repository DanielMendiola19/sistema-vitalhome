@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ========================================================= --}}
    {{-- ENCABEZADO --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">

        <div>

            <h2 class="fw-bold text-primary-vital mb-1">
                Registrar nueva cita
            </h2>

            <p class="text-secondary-vital mb-0">
                Programa una cita o recojo de medicamentos para un paciente.
            </p>

        </div>

        <a
            href="{{ route('citas.index') }}"
            class="btn btn-outline-secondary"
        >
            <i class="bi bi-arrow-left me-1"></i>
            Volver
        </a>

    </div>


    {{-- ========================================================= --}}
    {{-- ERRORES --}}
    {{-- ========================================================= --}}

    @if($errors->any())

        <div
            class="alert alert-danger alert-dismissible fade show"
            role="alert"
        >

            <div class="fw-semibold mb-2">

                <i class="bi bi-exclamation-triangle-fill me-2"></i>

                Revisa los siguientes datos:

            </div>

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif


    {{-- ========================================================= --}}
    {{-- FORMULARIO --}}
    {{-- ========================================================= --}}

    <div class="row justify-content-center">

        <div class="col-12 col-xl-9">

            <div class="card section-card">

                <div class="card-body p-4">


                    <form
                        action="{{ route('citas.store') }}"
                        method="POST"
                    >

                        @csrf


                        {{-- ================================================= --}}
                        {{-- PACIENTE --}}
                        {{-- ================================================= --}}

                        <div class="mb-4">

                            <label
                                for="paciente_id"
                                class="form-label fw-semibold"
                            >
                                Paciente
                                <span class="text-danger">*</span>
                            </label>

                            <select
                                name="paciente_id"
                                id="paciente_id"
                                class="form-select @error('paciente_id') is-invalid @enderror"
                                required
                            >

                                <option value="">
                                    Selecciona un paciente
                                </option>

                                @foreach($pacientes as $paciente)

                                    <option
                                        value="{{ $paciente->id }}"
                                        {{ old('paciente_id') == $paciente->id ? 'selected' : '' }}
                                    >

                                        {{ $paciente->nombre }}
                                        {{ $paciente->apellido }}

                                        @if($paciente->ci)
                                            - CI: {{ $paciente->ci }}
                                        @endif

                                    </option>

                                @endforeach

                            </select>

                            @error('paciente_id')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- ================================================= --}}
                        {{-- TIPO DE CITA --}}
                        {{-- ================================================= --}}

                        <div class="mb-4">

                            <label class="form-label fw-semibold">

                                Tipo de cita
                                <span class="text-danger">*</span>

                            </label>


                            <div class="row g-3">


                                {{-- ASISTIR A CITA --}}

                                <div class="col-12 col-md-6">

                                    <label
                                        for="tipo_asistir"
                                        class="border rounded-3 p-3 w-100 h-100"
                                        style="cursor:pointer;"
                                    >

                                        <div class="d-flex align-items-start">

                                            <input
                                                class="form-check-input me-3 mt-1"
                                                type="radio"
                                                name="tipo"
                                                id="tipo_asistir"
                                                value="asistir_cita"
                                                {{ old('tipo') === 'asistir_cita' ? 'checked' : '' }}
                                                required
                                            >

                                            <div>

                                                <div class="fw-bold">

                                                    <i class="bi bi-person-check me-1 text-primary"></i>

                                                    Asistir a cita

                                                </div>

                                                <small class="text-secondary-vital">
                                                    Cita médica o de seguimiento del paciente.
                                                </small>

                                            </div>

                                        </div>

                                    </label>

                                </div>


                                {{-- RECOGER MEDICAMENTOS --}}

                                <div class="col-12 col-md-6">

                                    <label
                                        for="tipo_medicamentos"
                                        class="border rounded-3 p-3 w-100 h-100"
                                        style="cursor:pointer;"
                                    >

                                        <div class="d-flex align-items-start">

                                            <input
                                                class="form-check-input me-3 mt-1"
                                                type="radio"
                                                name="tipo"
                                                id="tipo_medicamentos"
                                                value="recoger_medicamentos"
                                                {{ old('tipo') === 'recoger_medicamentos' ? 'checked' : '' }}
                                                required
                                            >

                                            <div>

                                                <div class="fw-bold">

                                                    <i class="bi bi-capsule me-1 text-success"></i>

                                                    Recoger medicamentos

                                                </div>

                                                <small class="text-secondary-vital">
                                                    Programar el recojo de medicamentos del paciente.
                                                </small>

                                            </div>

                                        </div>

                                    </label>

                                </div>

                            </div>

                            @error('tipo')

                                <div class="text-danger small mt-2">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- ================================================= --}}
                        {{-- FECHA Y HORA --}}
                        {{-- ================================================= --}}

                        <div class="row g-3 mb-4">


                            {{-- FECHA --}}

                            <div class="col-12 col-md-6">

                                <label
                                    for="fecha"
                                    class="form-label fw-semibold"
                                >
                                    Fecha
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="date"
                                    name="fecha"
                                    id="fecha"
                                    value="{{ old('fecha') }}"
                                    class="form-control @error('fecha') is-invalid @enderror"
                                    required
                                >

                                @error('fecha')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>


                            {{-- HORA --}}

                            <div class="col-12 col-md-6">

                                <label
                                    for="hora"
                                    class="form-label fw-semibold"
                                >
                                    Hora
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="time"
                                    name="hora"
                                    id="hora"
                                    value="{{ old('hora') }}"
                                    class="form-control @error('hora') is-invalid @enderror"
                                    required
                                >

                                @error('hora')

                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>

                                @enderror

                            </div>

                        </div>


                        {{-- ================================================= --}}
                        {{-- OBSERVACIONES --}}
                        {{-- ================================================= --}}

                        <div class="mb-4">

                            <label
                                for="observaciones"
                                class="form-label fw-semibold"
                            >
                                Observaciones
                            </label>

                            <textarea
                                name="observaciones"
                                id="observaciones"
                                rows="5"
                                maxlength="2000"
                                class="form-control @error('observaciones') is-invalid @enderror"
                                placeholder="Agrega información adicional sobre la cita..."
                            >{{ old('observaciones') }}</textarea>

                            <div class="form-text">
                                Opcional. Máximo 2000 caracteres.
                            </div>

                            @error('observaciones')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- ================================================= --}}
                        {{-- BOTONES --}}
                        {{-- ================================================= --}}

                        <div class="d-flex justify-content-end gap-2">

                            <a
                                href="{{ route('citas.index') }}"
                                class="btn btn-light"
                            >
                                Cancelar
                            </a>


                            <button
                                type="submit"
                                class="btn btn-success"
                            >

                                <i class="bi bi-calendar-plus me-1"></i>

                                Registrar cita

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
