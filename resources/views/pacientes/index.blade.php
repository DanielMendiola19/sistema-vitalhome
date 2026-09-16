@extends('layouts.app')

@section('title', 'Pacientes')

@section('content')

<style>
    .patients-page {
        background: #f8fafc;
        min-height: calc(100vh - 70px);
        padding: 32px;
    }

    .patients-header {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 24px;
        margin-bottom: 28px;
    }

    .patients-title {
        color: #0f172a;
        font-size: 30px;
        font-weight: 700;
        margin: 0;
    }

    .patients-subtitle {
        color: #64748b;
        font-size: 14px;
        margin: 7px 0 0;
    }

    .btn-new-patient {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #10b981;
        border: 1px solid #10b981;
        color: white;
        padding: 11px 17px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        transition: .2s;
    }

    .btn-new-patient:hover {
        background: #059669;
        border-color: #059669;
        color: white;
    }

    .patients-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 18px;
    }

    .search-form {
        flex: 1;
        max-width: 520px;
    }

    .search-wrapper {
        position: relative;
    }

    .search-wrapper i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
    }

    .search-input {
        width: 100%;
        height: 44px;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        background: white;
        padding: 0 16px 0 43px;
        font-size: 14px;
        outline: none;
    }

    .search-input:focus {
        border-color: #10b981;
        box-shadow: 0 0 0 3px rgba(16,185,129,.1);
    }

    .status-tabs {
        display: inline-flex;
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 4px;
    }

    .status-tab {
        border: none;
        background: transparent;
        color: #64748b;
        padding: 8px 15px;
        border-radius: 7px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
    }

    .status-tab.active {
        background: #0f172a;
        color: white;
    }

    .patients-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        box-shadow: 0 2px 8px rgba(15,23,42,.04);
        overflow: hidden;
    }

    .patients-table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .patients-table {
        width: 100%;
        min-width: 1050px;
        border-collapse: collapse;
    }

    .patients-table thead {
        background: #f8fafc;
    }

    .patients-table th {
        padding: 14px 18px;
        color: #64748b;
        font-size: 11px;
        font-weight: 700;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
    }

    .patients-table td {
        padding: 17px 18px;
        border-bottom: 1px solid #f1f5f9;
        color: #475569;
        font-size: 14px;
        vertical-align: middle;
    }

    .patients-table tbody tr:hover {
        background: #f8fafc;
    }

    .patient-name {
        color: #0f172a;
        font-weight: 700;
        text-decoration: none;
    }

    .patient-name:hover {
        color: #059669;
    }

    .patient-ci {
        color: #94a3b8;
        font-size: 12px;
        margin-top: 3px;
    }

    .clinical-info {
        max-width: 260px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
    }

    .status-badge::before {
        content: '';
        width: 6px;
        height: 6px;
        border-radius: 50%;
    }

    .status-active {
        background: #dcfce7;
        color: #15803d;
    }

    .status-active::before {
        background: #22c55e;
    }

    .status-inactive {
        background: #f1f5f9;
        color: #64748b;
    }

    .status-inactive::before {
        background: #94a3b8;
    }

    .med-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 32px;
        height: 28px;
        padding: 0 8px;
        border-radius: 7px;
        background: #f0fdf4;
        color: #059669;
        font-weight: 700;
    }

    .profile-link {
        color: #059669;
        font-weight: 700;
        text-decoration: none;
        white-space: nowrap;
    }

    .profile-link:hover {
        color: #047857;
    }

    .results-info {
        color: #64748b;
        font-size: 13px;
        margin-bottom: 12px;
    }

    .empty-state {
        padding: 70px 30px;
        text-align: center;
    }

    .empty-icon {
        width: 55px;
        height: 55px;
        border-radius: 50%;
        background: #f1f5f9;
        color: #94a3b8;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        margin-bottom: 16px;
    }

    .form-label-custom {
        color: #334155;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 6px;
    }

    .form-control-custom {
        border: 1px solid #e2e8f0;
        border-radius: 9px;
        min-height: 43px;
        font-size: 14px;
    }

    .form-control-custom:focus {
        border-color: #10b981;
        box-shadow: 0 0 0 3px rgba(16,185,129,.1);
    }

    .modal-content {
        border: none;
        border-radius: 15px;
        box-shadow: 0 20px 50px rgba(15,23,42,.18);
    }

    .modal-header {
        border-bottom: 1px solid #f1f5f9;
        padding: 20px 24px;
    }

    .modal-title {
        color: #0f172a;
        font-size: 18px;
        font-weight: 700;
    }

    .modal-body {
        padding: 24px;
    }

    .modal-footer {
        border-top: 1px solid #f1f5f9;
        padding: 16px 24px;
    }

    .btn-save {
        background: #10b981;
        border-color: #10b981;
        color: white;
        font-weight: 600;
        border-radius: 9px;
        padding: 10px 18px;
    }

    .btn-save:hover {
        background: #059669;
        border-color: #059669;
        color: white;
    }

    @media (max-width: 768px) {

        .patients-page {
            padding: 20px 15px;
        }

        .patients-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .btn-new-patient {
            width: 100%;
            justify-content: center;
        }

        .patients-toolbar {
            align-items: stretch;
            flex-direction: column;
        }

        .search-form {
            max-width: none;
        }

        .status-tabs {
            width: 100%;
        }

        .status-tab {
            flex: 1;
            text-align: center;
        }
    }
</style>


<div class="patients-page">

    {{-- HEADER --}}
    <div class="patients-header">

        <div>
            <h1 class="patients-title">
                Pacientes
            </h1>

            <p class="patients-subtitle">
                Gestión de pacientes registrados en VITALHOME
            </p>
        </div>

        <button
            type="button"
            class="btn-new-patient"
            data-bs-toggle="modal"
            data-bs-target="#nuevoPacienteModal"
        >
            <i class="bi bi-plus-lg"></i>
            Nuevo paciente
        </button>

    </div>


    {{-- MENSAJE DE ÉXITO --}}
    @if(session('success'))

        <div class="alert alert-success border-0 shadow-sm mb-3">

            <i class="bi bi-check-circle me-2"></i>

            {{ session('success') }}

        </div>

    @endif


    {{-- BÚSQUEDA + FILTROS --}}
    <div class="patients-toolbar">

        <form
            action="{{ route('pacientes.index') }}"
            method="GET"
            class="search-form"
        >

            @if($estado !== 'Todos')

                <input
                    type="hidden"
                    name="estado"
                    value="{{ $estado }}"
                >

            @endif

            <div class="search-wrapper">

                <i class="bi bi-search"></i>

                <input
                    type="text"
                    name="buscar"
                    value="{{ $buscar }}"
                    class="search-input"
                    placeholder="Buscar por nombre o carnet..."
                >

            </div>

        </form>


        <div class="status-tabs">

            <a
                href="{{ route('pacientes.index', ['buscar' => $buscar]) }}"
                class="status-tab {{ $estado === 'Todos' ? 'active' : '' }}"
            >
                Todos
            </a>

            <a
                href="{{ route('pacientes.index', ['buscar' => $buscar, 'estado' => 'Activos']) }}"
                class="status-tab {{ $estado === 'Activos' ? 'active' : '' }}"
            >
                Activos
            </a>

            <a
                href="{{ route('pacientes.index', ['buscar' => $buscar, 'estado' => 'Inactivos']) }}"
                class="status-tab {{ $estado === 'Inactivos' ? 'active' : '' }}"
            >
                Inactivos
            </a>

        </div>

    </div>


    <div class="results-info">

        Mostrando

        <strong>
            {{ $pacientes->count() }}
        </strong>

        {{ $pacientes->count() === 1 ? 'paciente' : 'pacientes' }}

    </div>


    {{-- TABLA --}}
    <div class="patients-card">

        @if($pacientes->count())

            <div class="patients-table-wrapper">

                <table class="patients-table">

                    <thead>

                        <tr>

                            <th>
                                NOMBRE COMPLETO
                            </th>

                            <th>
                                EDAD
                            </th>

                            <th>
                                INFORMACIÓN CLÍNICA
                            </th>

                            <th>
                                RESIDENCIA
                            </th>

                            <th>
                                MEDICAMENTOS ACTIVOS
                            </th>

                            <th>
                                ÚLTIMA ACTUALIZACIÓN
                            </th>

                            <th>
                                ACCIONES
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach($pacientes as $paciente)

                            @php
                                $edad = $paciente->fecha_nacimiento
                                    ? $paciente->fecha_nacimiento->age
                                    : null;
                            @endphp

                            <tr>

                                {{-- NOMBRE --}}
                                <td>

                                    <a
                                        href="{{ route('pacientes.show', $paciente->id) }}"
                                        class="patient-name"
                                    >
                                        {{ $paciente->nombre }}
                                        {{ $paciente->apellido }}
                                    </a>

                                    <div class="patient-ci">
                                        CI: {{ $paciente->ci }}
                                    </div>

                                </td>


                                {{-- EDAD --}}
                                <td>

                                    @if($edad !== null)

                                        {{ $edad }} años

                                    @else

                                        —

                                    @endif

                                </td>


                                {{-- INFORMACIÓN CLÍNICA --}}
                                <td>

                                    <div
                                        class="clinical-info"
                                        title="{{ $paciente->observaciones }}"
                                    >

                                        {{ $paciente->observaciones ?: 'Sin observaciones registradas' }}

                                    </div>

                                </td>


                                {{-- ESTADO --}}
                                <td>

                                    @if($paciente->estado === 'activo')

                                        <span class="status-badge status-active">
                                            Activo
                                        </span>

                                    @else

                                        <span class="status-badge status-inactive">
                                            Inactivo
                                        </span>

                                    @endif

                                </td>


                                {{-- MEDICAMENTOS --}}
                                <td>

                                    <span class="med-count">
                                        {{ $paciente->tratamientos->count() }}
                                    </span>

                                </td>


                                {{-- ACTUALIZACIÓN --}}
                                <td>

                                    {{ $paciente->updated_at
                                        ? $paciente->updated_at->format('d/m/Y H:i')
                                        : '—'
                                    }}

                                </td>


                                {{-- ACCIONES --}}
                                <td>

                                    <a
                                        href="{{ route('pacientes.show', $paciente->id) }}"
                                        class="profile-link"
                                    >
                                        Ver perfil →
                                    </a>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            <div class="empty-state">

                <div class="empty-icon">
                    <i class="bi bi-person-x"></i>
                </div>

                <h5 class="fw-bold text-dark">
                    No se encontraron pacientes
                </h5>

                <p class="text-secondary mb-0">
                    Intenta modificar los filtros o registrar un nuevo paciente.
                </p>

            </div>

        @endif

    </div>

</div>


{{-- ====================================================== --}}
{{-- MODAL NUEVO PACIENTE --}}
{{-- ====================================================== --}}

<div
    class="modal fade"
    id="nuevoPacienteModal"
    tabindex="-1"
    aria-labelledby="nuevoPacienteModalLabel"
    aria-hidden="true"
>

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <form
                action="{{ route('pacientes.store') }}"
                method="POST"
            >

                @csrf

                <div class="modal-header">

                    <div>

                        <h5
                            class="modal-title"
                            id="nuevoPacienteModalLabel"
                        >
                            Nuevo paciente
                        </h5>

                        <small class="text-secondary">
                            Registra la información del paciente.
                        </small>

                    </div>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Cerrar"
                    ></button>

                </div>


                <div class="modal-body">

                    <div class="row g-3">

                        {{-- NOMBRE --}}
                        <div class="col-md-6">

                            <label
                                for="nombre"
                                class="form-label-custom"
                            >
                                Nombre
                            </label>

                            <input
                                type="text"
                                id="nombre"
                                name="nombre"
                                value="{{ old('nombre') }}"
                                class="form-control form-control-custom @error('nombre') is-invalid @enderror"
                                required
                            >

                            @error('nombre')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- APELLIDO --}}
                        <div class="col-md-6">

                            <label
                                for="apellido"
                                class="form-label-custom"
                            >
                                Apellido
                            </label>

                            <input
                                type="text"
                                id="apellido"
                                name="apellido"
                                value="{{ old('apellido') }}"
                                class="form-control form-control-custom @error('apellido') is-invalid @enderror"
                                required
                            >

                            @error('apellido')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- CI --}}
                        <div class="col-md-6">

                            <label
                                for="ci"
                                class="form-label-custom"
                            >
                                Carnet de identidad
                            </label>

                            <input
                                type="text"
                                id="ci"
                                name="ci"
                                value="{{ old('ci') }}"
                                class="form-control form-control-custom @error('ci') is-invalid @enderror"
                                required
                            >

                            @error('ci')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- FECHA NACIMIENTO --}}
                        <div class="col-md-6">

                            <label
                                for="fecha_nacimiento"
                                class="form-label-custom"
                            >
                                Fecha de nacimiento
                            </label>

                            <input
                                type="date"
                                id="fecha_nacimiento"
                                name="fecha_nacimiento"
                                value="{{ old('fecha_nacimiento') }}"
                                class="form-control form-control-custom @error('fecha_nacimiento') is-invalid @enderror"
                                required
                            >

                            @error('fecha_nacimiento')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- SEXO --}}
                        <div class="col-md-6">

                            <label
                                for="sexo"
                                class="form-label-custom"
                            >
                                Sexo
                            </label>

                            <select
                                id="sexo"
                                name="sexo"
                                class="form-select form-control-custom @error('sexo') is-invalid @enderror"
                                required
                            >

                                <option value="">
                                    Seleccionar
                                </option>

                                <option
                                    value="Femenino"
                                    {{ old('sexo') === 'Femenino' ? 'selected' : '' }}
                                >
                                    Femenino
                                </option>

                                <option
                                    value="Masculino"
                                    {{ old('sexo') === 'Masculino' ? 'selected' : '' }}
                                >
                                    Masculino
                                </option>

                            </select>

                            @error('sexo')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- TELEFONO --}}
                        <div class="col-md-6">

                            <label
                                for="telefono"
                                class="form-label-custom"
                            >
                                Teléfono
                            </label>

                            <input
                                type="text"
                                id="telefono"
                                name="telefono"
                                value="{{ old('telefono') }}"
                                class="form-control form-control-custom @error('telefono') is-invalid @enderror"
                            >

                            @error('telefono')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- DIRECCIÓN --}}
                        <div class="col-12">

                            <label
                                for="direccion"
                                class="form-label-custom"
                            >
                                Dirección
                            </label>

                            <input
                                type="text"
                                id="direccion"
                                name="direccion"
                                value="{{ old('direccion') }}"
                                class="form-control form-control-custom @error('direccion') is-invalid @enderror"
                            >

                            @error('direccion')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- SEGURO --}}
                        <div class="col-md-6">
                            <label for="tiene_seguro" class="form-label-custom">
                                ¿Cuenta con seguro?
                            </label>

                            <select
                                id="tiene_seguro"
                                name="tiene_seguro"
                                class="form-select form-control-custom @error('tiene_seguro') is-invalid @enderror"
                                required
                            >
                                <option value="0" {{ old('tiene_seguro', '0') === '0' ? 'selected' : '' }}>
                                    No
                                </option>
                                <option value="1" {{ old('tiene_seguro') === '1' ? 'selected' : '' }}>
                                    Sí
                                </option>
                            </select>

                            @error('tiene_seguro')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- ESTADO --}}
                        <div class="col-md-6">
                            <label for="estado_paciente" class="form-label-custom">
                                Estado
                            </label>

                            <select
                                id="estado_paciente"
                                name="estado"
                                class="form-select form-control-custom @error('estado') is-invalid @enderror"
                                required
                            >
                                <option value="activo" {{ old('estado', 'activo') === 'activo' ? 'selected' : '' }}>
                                    Activo - Reside en VITALHOME
                                </option>
                                <option value="inactivo" {{ old('estado') === 'inactivo' ? 'selected' : '' }}>
                                    Inactivo
                                </option>
                            </select>

                            @error('estado')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- ESPECIFICAR SEGURO --}}
                        <div
                            class="col-12"
                            id="seguroDetalleContainer"
                            style="display:none;"
                        >
                            <label for="seguro" class="form-label-custom">
                                ¿A qué seguro pertenece?
                            </label>

                            <input
                                type="text"
                                id="seguro"
                                name="seguro"
                                value="{{ old('seguro') }}"
                                class="form-control form-control-custom @error('seguro') is-invalid @enderror"
                                placeholder="Ej. Caja Nacional de Salud"
                            >

                            @error('seguro')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- MOTIVO INACTIVIDAD --}}
                        <div
                            class="col-12"
                            id="motivoInactividadContainer"
                            style="display:none;"
                        >
                            <label for="motivo_inactividad" class="form-label-custom">
                                Motivo de inactividad
                            </label>

                            <select
                                id="motivo_inactividad"
                                name="motivo_inactividad"
                                class="form-select form-control-custom @error('motivo_inactividad') is-invalid @enderror"
                            >
                                <option value="">Seleccionar</option>
                                <option value="retiro" {{ old('motivo_inactividad') === 'retiro' ? 'selected' : '' }}>
                                    Ya no reside en VITALHOME
                                </option>
                                <option value="fallecimiento" {{ old('motivo_inactividad') === 'fallecimiento' ? 'selected' : '' }}>
                                    Falleció
                                </option>
                            </select>

                            @error('motivo_inactividad')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- ESPECIALIDADES --}}
                        <div class="col-12">
                            <label for="especialidades" class="form-label-custom">
                                Especialidades a las que acude
                            </label>

                            <textarea
                                id="especialidades"
                                name="especialidades"
                                rows="2"
                                class="form-control form-control-custom @error('especialidades') is-invalid @enderror"
                                placeholder="Ej. Cardiología, Neurología, Traumatología"
                            >{{ old('especialidades') }}</textarea>

                            @error('especialidades')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- MEDICAMENTOS DE INGRESO --}}
                        <div class="col-12">
                            <label for="medicamentos_ingreso" class="form-label-custom">
                                Medicamentos con los que ingresa
                            </label>

                            <textarea
                                id="medicamentos_ingreso"
                                name="medicamentos_ingreso"
                                rows="3"
                                class="form-control form-control-custom @error('medicamentos_ingreso') is-invalid @enderror"
                                placeholder="Registra los medicamentos con los que llega el paciente, dosis o indicaciones si corresponde."
                            >{{ old('medicamentos_ingreso') }}</textarea>

                            @error('medicamentos_ingreso')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- OBSERVACIONES --}}
                        <div class="col-12">

                            <label
                                for="observaciones"
                                class="form-label-custom"
                            >
                                Observaciones
                            </label>

                            <textarea
                                id="observaciones"
                                name="observaciones"
                                rows="3"
                                class="form-control form-control-custom @error('observaciones') is-invalid @enderror"
                            >{{ old('observaciones') }}</textarea>

                            @error('observaciones')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

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
                        class="btn btn-save"
                    >
                        <i class="bi bi-check2 me-1"></i>
                        Guardar paciente
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- ====================================================== --}}
{{-- CAMPOS CONDICIONALES DEL REGISTRO --}}
{{-- ====================================================== --}}

<script>
document.addEventListener('DOMContentLoaded', function () {
    const tieneSeguro = document.getElementById('tiene_seguro');
    const seguroContainer = document.getElementById('seguroDetalleContainer');
    const seguroInput = document.getElementById('seguro');

    const estadoPaciente = document.getElementById('estado_paciente');
    const motivoContainer = document.getElementById('motivoInactividadContainer');
    const motivoSelect = document.getElementById('motivo_inactividad');

    function actualizarSeguro() {
        const mostrar = tieneSeguro && tieneSeguro.value === '1';

        if (seguroContainer) {
            seguroContainer.style.display = mostrar ? '' : 'none';
        }

        if (seguroInput) {
            seguroInput.required = mostrar;

            if (!mostrar) {
                seguroInput.value = '';
            }
        }
    }

    function actualizarEstado() {
        const mostrar = estadoPaciente && estadoPaciente.value === 'inactivo';

        if (motivoContainer) {
            motivoContainer.style.display = mostrar ? '' : 'none';
        }

        if (motivoSelect) {
            motivoSelect.required = mostrar;

            if (!mostrar) {
                motivoSelect.value = '';
            }
        }
    }

    if (tieneSeguro) {
        tieneSeguro.addEventListener('change', actualizarSeguro);
        actualizarSeguro();
    }

    if (estadoPaciente) {
        estadoPaciente.addEventListener('change', actualizarEstado);
        actualizarEstado();
    }
});
</script>


{{-- ====================================================== --}}
{{-- REABRIR MODAL SI HUBO ERRORES --}}
{{-- ====================================================== --}}

@if($errors->any())

<script>
document.addEventListener('DOMContentLoaded', function () {

    const modalElement =
        document.getElementById('nuevoPacienteModal');

    if (modalElement) {

        const modal =
            new bootstrap.Modal(modalElement);

        modal.show();
    }

});
</script>

@endif

@endsection
