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

    .patient-actions {
        display: flex;
        flex-direction: column;
        align-items: stretch;
        gap: 7px;
        width: 112px;
        min-width: 112px;
    }

    .patient-action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;

        width: 100%;
        min-width: 0;
        height: 36px;
        padding: 0 10px;

        border-radius: 9px;
        font-size: 12px;
        font-weight: 700;
        line-height: 1;

        text-decoration: none;
        border: 1px solid transparent;
        cursor: pointer;
        transition: all .2s ease;
        white-space: nowrap;
    }

    .patient-action-view {
        background: #ecfdf5;
        border-color: #a7f3d0;
        color: #047857;
    }

    .patient-action-view:hover {
        background: #d1fae5;
        border-color: #6ee7b7;
        color: #065f46;
    }

    .patient-action-delete {
        background: #fff1f2;
        border-color: #fecdd3;
        color: #dc2626;
    }

    .patient-action-delete:hover {
        background: #ffe4e6;
        border-color: #fda4af;
        color: #b91c1c;
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


    .search-clear {
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translateY(-50%);
        border: 0;
        background: #f1f5f9;
        color: #64748b;
        border-radius: 7px;
        padding: 5px 10px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
    }

    .search-input.has-clear { padding-right: 86px; }

    .pagination-shell {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-top: 16px;
        padding: 14px 16px;
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
    }

    .pagination-info { color: #64748b; font-size: 13px; }
    .pagination-links { display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }
    .page-link-custom {
        min-width: 36px; height: 36px; padding: 0 10px;
        display: inline-flex; align-items: center; justify-content: center;
        border: 1px solid #e2e8f0; border-radius: 8px; background: white;
        color: #475569; text-decoration: none; font-size: 13px; font-weight: 700;
    }
    .page-link-custom:hover { border-color: #10b981; color: #059669; background: #f0fdf4; }
    .page-link-custom.active { background: #0f172a; border-color: #0f172a; color: white; }
    .page-link-custom.disabled { opacity: .45; pointer-events: none; }

    .action-group { display: flex; align-items: center; gap: 10px; white-space: nowrap; }
    .delete-link {
        border: 0; background: transparent; padding: 0;
        color: #dc2626; font-size: 13px; font-weight: 700;
    }
    .delete-link:hover { color: #991b1b; text-decoration: underline; }

    .field-help { color: #94a3b8; font-size: 11px; margin-top: 5px; }
    .live-feedback { display: none; font-size: 12px; margin-top: 5px; }
    .live-feedback.invalid { display: block; color: #dc2626; }
    .live-feedback.valid { display: block; color: #059669; }
    .form-control-custom.is-valid { border-color: #10b981; }
    .form-control-custom.is-invalid { border-color: #dc2626; }




    /* =========================================================
    MODAL ELIMINAR PACIENTE
    ========================================================= */

    .delete-modal {
        border: none;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 24px 60px rgba(15, 23, 42, .20);
    }

    .delete-modal-body {
        padding: 30px 26px 24px;
        text-align: center;
    }

    .delete-modal-icon {
        width: 58px;
        height: 58px;
        margin: 0 auto 17px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 50%;
        background: #fff1f2;
        color: #dc2626;
        font-size: 23px;
    }

    .delete-modal-title {
        margin: 0 0 10px;
        color: #0f172a;
        font-size: 19px;
        font-weight: 800;
    }

    .delete-modal-text {
        margin: 0;
        color: #64748b;
        font-size: 14px;
        line-height: 1.6;
    }

    .delete-modal-text strong {
        color: #0f172a;
    }

    .delete-modal-notice {
        display: flex;
        align-items: flex-start;
        gap: 9px;

        margin-top: 18px;
        padding: 12px 13px;

        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;

        color: #64748b;
        font-size: 12px;
        line-height: 1.5;
        text-align: left;
    }

    .delete-modal-notice i {
        color: #0ea5e9;
        margin-top: 1px;
    }

    .delete-modal-actions {
        display: flex;
        gap: 10px;
        margin-top: 22px;
    }

    .delete-modal-actions form {
        flex: 1;
        margin: 0;
    }

    .btn-delete-cancel,
    .btn-delete-confirm {
        width: 100%;
        min-height: 42px;

        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;

        border-radius: 9px;
        font-size: 13px;
        font-weight: 700;

        cursor: pointer;
        transition: all .2s ease;
    }

    .btn-delete-cancel {
        flex: 1;
        background: white;
        color: #475569;
        border: 1px solid #cbd5e1;
    }

    .btn-delete-cancel:hover {
        background: #f8fafc;
        color: #0f172a;
    }

    .btn-delete-confirm {
        background: #dc2626;
        color: white;
        border: 1px solid #dc2626;
    }

    .btn-delete-confirm:hover {
        background: #b91c1c;
        border-color: #b91c1c;
    }

    @media (max-width: 575.98px) {
        #eliminarPacienteModal .modal-dialog {
            margin: 16px;
        }

        .delete-modal-body {
            padding: 25px 18px 20px;
        }

        .delete-modal-actions {
            flex-direction: column-reverse;
        }

        .btn-delete-cancel,
        .btn-delete-confirm {
            min-height: 44px;
        }
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

        @if(in_array(auth()->user()->rol, ['administrador', 'doctor', 'enfermero']))
            <button
                type="button"
                class="btn-new-patient"
                data-bs-toggle="modal"
                data-bs-target="#nuevoPacienteModal"
            >
                <i class="bi bi-plus-lg"></i>
                Nuevo paciente
            </button>
        @endif

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
            id="patientSearchForm"
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
                    id="patientSearchInput"
                    value="{{ $buscar }}"
                    class="search-input {{ $buscar !== '' ? 'has-clear' : '' }}"
                    placeholder="Buscar por nombre, apellido o carnet..."
                    autocomplete="off"
                >

                <button
                    type="button"
                    id="clearPatientSearch"
                    class="search-clear"
                    style="{{ $buscar !== '' ? '' : 'display:none;' }}"
                >
                    Limpiar
                </button>

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
        @if($pacientes->total() > 0)
            Mostrando <strong>{{ $pacientes->firstItem() }}–{{ $pacientes->lastItem() }}</strong>
            de <strong>{{ $pacientes->total() }}</strong> pacientes
        @else
            No hay pacientes para mostrar
        @endif
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
                                        CI: {{ $paciente->ci ?: 'No registrado' }}
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
                                    <div class="patient-actions">

                                        <a
                                            href="{{ route('pacientes.show', $paciente->id) }}"
                                            class="patient-action-btn patient-action-view"
                                        >
                                            <i class="bi bi-eye"></i>
                                            Ver perfil
                                        </a>

                                        @if(in_array(auth()->user()->rol, ['administrador', 'doctor', 'enfermero']))
                                            <button
                                                type="button"
                                                class="patient-action-btn patient-action-delete"
                                                data-bs-toggle="modal"
                                                data-bs-target="#eliminarPacienteModal"
                                                data-paciente-id="{{ $paciente->id }}"
                                                data-paciente-nombre="{{ $paciente->nombre }} {{ $paciente->apellido }}"
                                            >
                                                <i class="bi bi-trash3"></i>
                                                Eliminar
                                            </button>
                                        @endif

                                    </div>
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

    @if($pacientes->hasPages())
        <div class="pagination-shell">
            <div class="pagination-info">
                Página {{ $pacientes->currentPage() }} de {{ $pacientes->lastPage() }}
            </div>

            <div class="pagination-links">
                <a
                    class="page-link-custom {{ $pacientes->onFirstPage() ? 'disabled' : '' }}"
                    href="{{ $pacientes->previousPageUrl() ?: '#' }}"
                    aria-label="Página anterior"
                >
                    <i class="bi bi-chevron-left"></i>
                </a>

                @php
                    $inicio = max(1, $pacientes->currentPage() - 2);
                    $fin = min($pacientes->lastPage(), $pacientes->currentPage() + 2);
                @endphp

                @for($pagina = $inicio; $pagina <= $fin; $pagina++)
                    <a
                        class="page-link-custom {{ $pagina === $pacientes->currentPage() ? 'active' : '' }}"
                        href="{{ $pacientes->url($pagina) }}"
                    >
                        {{ $pagina }}
                    </a>
                @endfor

                <a
                    class="page-link-custom {{ $pacientes->hasMorePages() ? '' : 'disabled' }}"
                    href="{{ $pacientes->nextPageUrl() ?: '#' }}"
                    aria-label="Página siguiente"
                >
                    <i class="bi bi-chevron-right"></i>
                </a>
            </div>
        </div>
    @endif

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
                id="newPatientForm"
                novalidate
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
                                maxlength="100"
                                autocomplete="given-name"
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
                                maxlength="100"
                                autocomplete="family-name"
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
                                maxlength="20"
                                autocomplete="off"
                            >

                            <div class="field-help">
                                Si aún no cuenta con carnet, déjalo vacío o escribe uno o más ceros. Se guardará como “sin CI”.
                            </div>
                            <div id="ciLiveFeedback" class="live-feedback"></div>

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
                                max="{{ now()->format('Y-m-d') }}"
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
                                maxlength="20"
                                autocomplete="tel"
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
                                maxlength="200"
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
                                maxlength="150"
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
                                maxlength="1000"
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
                                maxlength="5000"
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
        if (seguroContainer) seguroContainer.style.display = mostrar ? '' : 'none';
        if (seguroInput) {
            seguroInput.required = mostrar;
            if (!mostrar) seguroInput.value = '';
        }
    }

    function actualizarEstado() {
        const mostrar = estadoPaciente && estadoPaciente.value === 'inactivo';
        if (motivoContainer) motivoContainer.style.display = mostrar ? '' : 'none';
        if (motivoSelect) {
            motivoSelect.required = mostrar;
            if (!mostrar) motivoSelect.value = '';
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

    // Búsqueda automática con debounce para no disparar una petición por cada tecla.
    const searchForm = document.getElementById('patientSearchForm');
    const searchInput = document.getElementById('patientSearchInput');
    const clearSearch = document.getElementById('clearPatientSearch');
    let searchTimer = null;

    if (searchForm && searchInput) {
        searchInput.addEventListener('input', function () {
            clearTimeout(searchTimer);
            const hasValue = this.value.trim() !== '';
            this.classList.toggle('has-clear', hasValue);
            if (clearSearch) clearSearch.style.display = hasValue ? '' : 'none';
            searchTimer = setTimeout(() => searchForm.submit(), 450);
        });
    }

    if (clearSearch && searchForm && searchInput) {
        clearSearch.addEventListener('click', function () {
            searchInput.value = '';
            searchInput.classList.remove('has-clear');
            clearSearch.style.display = 'none';
            searchForm.submit();
        });
    }

    // Validación inmediata del formulario.
    const patientForm = document.getElementById('newPatientForm');
    const ciInput = document.getElementById('ci');
    const ciFeedback = document.getElementById('ciLiveFeedback');
    let ciTimer = null;
    let ciDuplicado = false;

    function feedbackFor(input) {
        let feedback = input.parentElement.querySelector('.live-feedback:not(#ciLiveFeedback)');
        if (!feedback) {
            feedback = document.createElement('div');
            feedback.className = 'live-feedback';
            input.parentElement.appendChild(feedback);
        }
        return feedback;
    }

    function validarCampo(input) {
        if (input === ciInput) return;
        const feedback = feedbackFor(input);
        input.classList.remove('is-valid', 'is-invalid');
        feedback.className = 'live-feedback';
        feedback.textContent = '';

        if (!input.value && !input.required) return;
        if (input.checkValidity()) {
            input.classList.add('is-valid');
        } else {
            input.classList.add('is-invalid');
            feedback.classList.add('invalid');
            feedback.textContent = input.validationMessage;
        }
    }

    if (patientForm) {
        patientForm.querySelectorAll('input, select, textarea').forEach(function (input) {
            if (input.type === 'hidden') return;
            input.addEventListener('input', () => validarCampo(input));
            input.addEventListener('change', () => validarCampo(input));
            input.addEventListener('blur', () => validarCampo(input));
        });
    }

    function mostrarEstadoCi(tipo, mensaje) {
        if (!ciInput || !ciFeedback) return;
        ciInput.classList.remove('is-valid', 'is-invalid');
        ciFeedback.className = 'live-feedback';
        ciFeedback.textContent = mensaje || '';
        if (!mensaje) return;
        if (tipo === 'invalid') {
            ciInput.classList.add('is-invalid');
            ciFeedback.classList.add('invalid');
        } else {
            ciInput.classList.add('is-valid');
            ciFeedback.classList.add('valid');
        }
    }

    async function verificarCi() {
        if (!ciInput) return;
        const ci = ciInput.value.trim();
        ciDuplicado = false;

        if (ci === '' || /^0+$/.test(ci)) {
            mostrarEstadoCi('valid', 'Se registrará como paciente sin CI.');
            return;
        }
        if (ci.length > 20) {
            mostrarEstadoCi('invalid', 'El CI no puede superar los 20 caracteres.');
            return;
        }

        mostrarEstadoCi('', '');
        try {
            const url = new URL(@json(route('pacientes.verificar-ci')), window.location.origin);
            url.searchParams.set('ci', ci);
            const response = await fetch(url, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (!response.ok) return;
            const data = await response.json();
            ciDuplicado = Boolean(data.exists);
            if (ciDuplicado) {
                mostrarEstadoCi('invalid', 'Este carnet de identidad ya está registrado.');
            } else {
                mostrarEstadoCi('valid', 'Carnet disponible.');
            }
        } catch (error) {
            // La validación del servidor al enviar sigue siendo la autoridad final.
        }
    }

    if (ciInput) {
        ciInput.addEventListener('input', function () {
            clearTimeout(ciTimer);
            ciTimer = setTimeout(verificarCi, 350);
        });
        ciInput.addEventListener('blur', verificarCi);
    }

    if (patientForm) {
        patientForm.addEventListener('submit', async function (event) {
            event.preventDefault();
            patientForm.querySelectorAll('input, select, textarea').forEach(validarCampo);
            await verificarCi();

            if (!patientForm.checkValidity() || ciDuplicado) {
                const firstInvalid = patientForm.querySelector('.is-invalid, :invalid');
                if (firstInvalid) firstInvalid.focus();
                return;
            }

            patientForm.submit();
        });
    }

});
</script>


{{-- ====================================================== --}}
{{-- MODAL ELIMINAR PACIENTE --}}
{{-- ====================================================== --}}

<div
    class="modal fade"
    id="eliminarPacienteModal"
    tabindex="-1"
    aria-labelledby="eliminarPacienteModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content delete-modal">

            <div class="modal-body delete-modal-body">

                <div class="delete-modal-icon">
                    <i class="bi bi-trash3"></i>
                </div>

                <h5
                    class="delete-modal-title"
                    id="eliminarPacienteModalLabel"
                >
                    Eliminar paciente
                </h5>

                <p class="delete-modal-text">
                    ¿Estás seguro de que deseas eliminar a
                    <strong id="nombrePacienteEliminar"></strong>?
                </p>

                <div class="delete-modal-notice">
                    <i class="bi bi-info-circle"></i>

                    <span>
                        El paciente dejará de aparecer en el sistema,
                        pero su historial quedará conservado.
                    </span>
                </div>

                <div class="delete-modal-actions">

                    <button
                        type="button"
                        class="btn-delete-cancel"
                        data-bs-dismiss="modal"
                    >
                        Cancelar
                    </button>

                    <form
                        id="formEliminarPaciente"
                        method="POST"
                        action=""
                    >
                        @csrf
                        @method('DELETE')

                        <button
                            type="submit"
                            class="btn-delete-confirm"
                        >
                            <i class="bi bi-trash3"></i>
                            Sí, eliminar
                        </button>
                    </form>

                </div>

            </div>

        </div>
    </div>
</div>

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


<script>
document.addEventListener('DOMContentLoaded', function () {

    const modalEliminar = document.getElementById('eliminarPacienteModal');

    if (!modalEliminar) {
        return;
    }

    modalEliminar.addEventListener('show.bs.modal', function (event) {

        const boton = event.relatedTarget;

        if (!boton) {
            return;
        }

        const pacienteId = boton.getAttribute('data-paciente-id');
        const pacienteNombre = boton.getAttribute('data-paciente-nombre');

        const nombreElement =
            document.getElementById('nombrePacienteEliminar');

        const formulario =
            document.getElementById('formEliminarPaciente');

        if (nombreElement) {
            nombreElement.textContent = pacienteNombre;
        }

        if (formulario) {
            formulario.action =
                "{{ url('/pacientes') }}/" + pacienteId;
        }

    });

});
</script>

@endsection
