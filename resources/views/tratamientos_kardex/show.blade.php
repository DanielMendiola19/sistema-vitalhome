@extends('layouts.app')

@section('title', 'Tratamientos / Kardex')

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
        text-decoration: none;
    }

    .btn-new-patient:hover {
        background: #059669;
        border-color: #059669;
        color: white;
    }

    .patients-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        box-shadow: 0 2px 8px rgba(15,23,42,.04);
        overflow: hidden;
        margin-bottom: 22px;
    }

    .patient-header-card {
        padding: 22px 24px;
        border-bottom: 1px solid #f1f5f9;
    }

    .patient-header-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
    }

    .info-label {
        color: #94a3b8;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 5px;
    }

    .info-value {
        color: #0f172a;
        font-size: 14px;
        font-weight: 600;
    }

    .diagnosis-value {
        color: #0f172a;
        font-size: 14px;
        font-weight: 600;
        line-height: 1.5;
    }

    .diagnosis-wrapper {
        display: flex;
        align-items: flex-start;
        gap: 8px;
    }

    .diagnosis-edit-btn {
        border: none;
        background: #f8fafc;
        color: #64748b;
        width: 30px;
        height: 30px;
        border-radius: 7px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: .2s;
    }

    .diagnosis-edit-btn:hover {
        background: #ecfdf5;
        color: #059669;
    }

    .kardex-title {
        color: #0f172a;
        font-size: 18px;
        font-weight: 700;
    }

    .kardex-subtitle {
        color: #64748b;
        font-size: 13px;
        margin-top: 4px;
    }

    .patients-table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .patients-table {
        width: 100%;
        min-width: 1400px;
        border-collapse: collapse;
    }

    .patients-table thead {
        background: #f8fafc;
    }

    .patients-table th {
        padding: 13px 15px;
        color: #64748b;
        font-size: 10px;
        font-weight: 700;
        text-align: center;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
    }

    .patients-table th:first-child,
    .patients-table th:nth-child(2),
    .patients-table th:nth-child(10) {
        text-align: left;
    }

    .patients-table td {
        padding: 16px 15px;
        border-bottom: 1px solid #f1f5f9;
        color: #475569;
        font-size: 13px;
        vertical-align: middle;
        text-align: center;
    }

    .patients-table td:first-child,
    .patients-table td:nth-child(2),
    .patients-table td:nth-child(10) {
        text-align: left;
    }

    .patients-table tbody tr:hover {
        background: #f8fafc;
    }

    .medication-name {
        color: #0f172a;
        font-weight: 700;
    }

    .medication-detail {
        color: #64748b;
        font-size: 12px;
        margin-top: 3px;
    }

    .hour-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 38px;
        height: 28px;
        padding: 0 7px;
        border-radius: 7px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #334155;
        font-size: 12px;
        font-weight: 600;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
    }

    .status-active {
        background: #dcfce7;
        color: #15803d;
    }

    .status-suspended {
        background: #fef3c7;
        color: #a16207;
    }

    .status-finished {
        background: #f1f5f9;
        color: #64748b;
    }

    .actions-wrapper {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
    }

    .action-btn {
        border: none;
        background: transparent;
        font-size: 16px;
        padding: 6px;
        color: #dc2626;
        transition: .2s;
    }

    .action-btn:hover {
        background: #fef2f2;
        border-radius: 7px;
        color: #b91c1c;
    }

    .action-btn-edit {
        color: #2563eb;
    }

    .action-btn-edit:hover {
        background: #eff6ff;
        color: #1d4ed8;
    }

    .action-btn-finished {
        color: #94a3b8;
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

    .btn-edit-save {
        background: #2563eb;
        border-color: #2563eb;
        color: white;
        font-weight: 600;
        border-radius: 9px;
        padding: 10px 18px;
    }

    .btn-edit-save:hover {
        background: #1d4ed8;
        border-color: #1d4ed8;
        color: white;
    }

    .diagnosis-textarea {
        min-height: 120px;
        resize: vertical;
    }

    @media (max-width: 768px) {

        .patients-page {
            padding: 20px 15px;
        }

        .patients-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .patient-header-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .btn-new-patient {
            width: 100%;
            justify-content: center;
        }

    }

</style>

<div class="patients-page">

    {{-- ====================================================== --}}
    {{-- HEADER --}}
    {{-- ====================================================== --}}

    <div class="patients-header">

        <div>

            <h1 class="patients-title">
                Tratamientos / Kardex
            </h1>

            <p class="patients-subtitle">
                Registro de medicamentos y tratamiento del paciente
            </p>

        </div>

        <a
            href="{{ route('tratamientos_kardex.lista') }}"
            class="btn btn-light"
        >

            <i class="bi bi-arrow-left me-1"></i>

            Volver

        </a>

    </div>


    {{-- ====================================================== --}}
    {{-- MENSAJE --}}
    {{-- ====================================================== --}}

    @if(session('success'))

        <div class="alert alert-success border-0 shadow-sm mb-3">

            <i class="bi bi-check-circle me-2"></i>

            {{ session('success') }}

        </div>

    @endif


    {{-- ====================================================== --}}
    {{-- ERRORES DE VALIDACIÓN --}}
    {{-- ====================================================== --}}

    @if($errors->any())

        <div class="alert alert-danger border-0 shadow-sm mb-3">

            <div class="fw-bold mb-2">

                <i class="bi bi-exclamation-triangle me-2"></i>

                No se pudo guardar la información.

            </div>

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- ====================================================== --}}
    {{-- DATOS DEL PACIENTE --}}
    {{-- ====================================================== --}}

    <div class="patients-card">

        <div class="patient-header-card">

            @php

                $edad = null;

                if ($paciente->fecha_nacimiento) {

                    $edad = \Carbon\Carbon::parse(
                        $paciente->fecha_nacimiento
                    )->age;

                }

            @endphp

            <div class="patient-header-grid">

                {{-- PACIENTE --}}

                <div>

                    <div class="info-label">
                        Paciente
                    </div>

                    <div class="info-value">

                        {{ $paciente->nombre }}
                        {{ $paciente->apellido }}

                    </div>

                </div>


                {{-- DIAGNÓSTICO --}}

                <div>

                    <div class="info-label">
                        Diagnóstico
                    </div>

                    <div class="diagnosis-wrapper">

                        <div class="diagnosis-value">

                            {{ $paciente->diagnostico ?: '—' }}

                        </div>

                        <button
                            type="button"
                            class="diagnosis-edit-btn"
                            title="Editar diagnóstico"
                            data-bs-toggle="modal"
                            data-bs-target="#editarDiagnosticoModal"
                        >

                            <i class="bi bi-pencil"></i>

                        </button>

                    </div>

                </div>


                {{-- EDAD --}}

                <div>

                    <div class="info-label">
                        Edad
                    </div>

                    <div class="info-value">

                        {{ $edad !== null ? $edad . ' años' : '—' }}

                    </div>

                </div>


                {{-- FECHA --}}

                <div>

                    <div class="info-label">
                        Fecha
                    </div>

                    <div class="info-value">

                        {{ now()->format('d/m/Y') }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ====================================================== --}}
    {{-- KARDEX --}}
    {{-- ====================================================== --}}

    <div class="patients-card">

        {{-- CABECERA DE TABLA --}}

        <div class="p-4 border-bottom">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <div class="kardex-title">
                        Hoja de Tratamientos
                    </div>

                    <div class="kardex-subtitle">
                        Medicamentos indicados y horarios de administración
                    </div>

                </div>

                <button
                    type="button"
                    class="btn-new-patient"
                    data-bs-toggle="modal"
                    data-bs-target="#nuevoTratamientoModal"
                >

                    <i class="bi bi-plus-lg"></i>

                    Nuevo tratamiento

                </button>

            </div>

        </div>


        @if($paciente->tratamientos->count())

            <div class="patients-table-wrapper">

                <table class="patients-table">

                    <thead>

                        <tr>

                            <th>
                                FECHA
                            </th>

                            <th>
                                MEDICAMENTO
                            </th>

                            <th>
                                DOSIS
                            </th>

                            <th>
                                VÍA
                            </th>

                            <th>
                                TM
                            </th>

                            <th>
                                TT
                            </th>

                            <th>
                                TN
                            </th>

                            <th>
                                HORARIOS
                            </th>

                            <th>
                                DURACIÓN
                            </th>

                            <th>
                                OBSERVACIONES
                            </th>

                            <th>
                                ESTADO
                            </th>

                            <th>
                                ACCIONES
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach($paciente->tratamientos as $tratamiento)

                            <tr>

                                {{-- FECHA --}}

                                <td>

                                    {{ $tratamiento->fecha_inicio
                                        ? $tratamiento->fecha_inicio->format('d/m/Y')
                                        : '—'
                                    }}

                                </td>


                                {{-- MEDICAMENTO --}}

                                <td>

                                    <div class="medication-name">

                                        {{ $tratamiento->medicamento->nombre
                                            ?? 'Medicamento no disponible'
                                        }}

                                    </div>

                                    @if(
                                        $tratamiento->medicamento &&
                                        $tratamiento->medicamento->concentracion
                                    )

                                        <div class="medication-detail">

                                            {{ $tratamiento->medicamento->concentracion }}

                                        </div>

                                    @endif

                                </td>


                                {{-- DOSIS --}}

                                <td>

                                    <strong>

                                        {{ $tratamiento->dosis ?? '—' }}

                                    </strong>

                                </td>


                                {{-- VÍA --}}

                                <td>

                                    {{ $tratamiento->via_administracion ?? '—' }}

                                </td>


                                {{-- TM --}}

                                <td>

                                    <span class="hour-badge">

                                        {{ $tratamiento->hora_tm
                                            ? $tratamiento->hora_tm->format('H:i')
                                            : '—'
                                        }}

                                    </span>

                                </td>


                                {{-- TT --}}

                                <td>

                                    <span class="hour-badge">

                                        {{ $tratamiento->hora_tt
                                            ? $tratamiento->hora_tt->format('H:i')
                                            : '—'
                                        }}

                                    </span>

                                </td>


                                {{-- TN --}}

                                <td>

                                    <span class="hour-badge">

                                        {{ $tratamiento->hora_tn
                                            ? $tratamiento->hora_tn->format('H:i')
                                            : '—'
                                        }}

                                    </span>

                                </td>


                                {{-- HORARIOS --}}

                                <td>

                                    @php

                                        $horarios = collect([
                                            $tratamiento->hora_tm
                                                ? $tratamiento->hora_tm->format('H:i')
                                                : null,

                                            $tratamiento->hora_tt
                                                ? $tratamiento->hora_tt->format('H:i')
                                                : null,

                                            $tratamiento->hora_tn
                                                ? $tratamiento->hora_tn->format('H:i')
                                                : null,

                                        ])->filter()->implode(' / ');

                                    @endphp

                                    {{ $horarios ?: '—' }}

                                </td>


                                {{-- DURACIÓN --}}

                                <td>

                                    @if($tratamiento->fecha_fin)

                                        Hasta
                                        {{ $tratamiento->fecha_fin->format('d/m/Y') }}

                                    @else

                                        Continuo

                                    @endif

                                </td>


                                {{-- OBSERVACIONES --}}

                                <td>

                                    {{ $tratamiento->indicaciones ?? '—' }}

                                </td>


                                {{-- ESTADO --}}

                                <td>

                                    @if($tratamiento->estado === 'activo')

                                        <span class="status-badge status-active">

                                            Activo

                                        </span>

                                    @elseif($tratamiento->estado === 'suspendido')

                                        <span class="status-badge status-suspended">

                                            Suspendido

                                        </span>

                                    @else

                                        <span class="status-badge status-finished">

                                            Finalizado

                                        </span>

                                    @endif

                                </td>


                                {{-- ACCIONES --}}

                                <td>

                                    <div class="actions-wrapper">

                                        {{-- EDITAR --}}

                                        <button
                                            type="button"
                                            class="action-btn action-btn-edit"
                                            title="Editar tratamiento"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editarTratamiento{{ $tratamiento->id }}"
                                        >

                                            <i class="bi bi-pencil-square"></i>

                                        </button>


                                        {{-- FINALIZAR --}}

                                        @if($tratamiento->estado !== 'finalizado')

                                            <button
                                                type="button"
                                                class="action-btn"
                                                title="Finalizar tratamiento"
                                                data-bs-toggle="modal"
                                                data-bs-target="#finalizarTratamiento{{ $tratamiento->id }}"
                                            >

                                                <i class="bi bi-check-circle"></i>

                                            </button>

                                        @else

                                            <span
                                                class="action-btn action-btn-finished"
                                                title="Tratamiento finalizado"
                                            >

                                                <i class="bi bi-check-circle-fill"></i>

                                            </span>

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

                    <i class="bi bi-capsule"></i>

                </div>

                <h5 class="fw-bold text-dark">

                    No hay tratamientos registrados

                </h5>

                <p class="text-secondary mb-0">

                    Registra el primer tratamiento de este paciente.

                </p>

            </div>

        @endif

    </div>

</div>


{{-- ====================================================== --}}
{{-- MODAL EDITAR DIAGNÓSTICO --}}
{{-- ====================================================== --}}

<div
    class="modal fade"
    id="editarDiagnosticoModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <form
                action="{{ route(
                    'tratamientos_kardex.diagnostico',
                    ['paciente' => $paciente->id]
                ) }}"
                method="POST"
            >

                @csrf

                @method('PATCH')

                <div class="modal-header">

                    <div>

                        <h5 class="modal-title">
                            Editar diagnóstico
                        </h5>

                        <small class="text-secondary">

                            Actualiza el diagnóstico inicial del paciente.

                        </small>

                    </div>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                    ></button>

                </div>


                <div class="modal-body">

                    <label class="form-label-custom">

                        Diagnóstico

                    </label>

                    <textarea
                        name="diagnostico"
                        rows="5"
                        class="form-control form-control-custom diagnosis-textarea"
                        placeholder="Ingrese el diagnóstico del paciente..."
                    >{{ old('diagnostico', $paciente->diagnostico) }}</textarea>

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

                        Guardar diagnóstico

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- ====================================================== --}}
{{-- MODAL NUEVO TRATAMIENTO --}}
{{-- ====================================================== --}}

<div
    class="modal fade"
    id="nuevoTratamientoModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <form
                action="{{ route(
                    'tratamientos_kardex.store',
                    ['paciente' => $paciente->id]
                ) }}"
                method="POST"
            >

                @csrf

                <div class="modal-header">

                    <div>

                        <h5 class="modal-title">
                            Nuevo tratamiento
                        </h5>

                        <small class="text-secondary">

                            Registra la indicación médica del paciente.

                        </small>

                    </div>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                    ></button>

                </div>


                <div class="modal-body">

                    <div class="row g-3">

                        {{-- MEDICAMENTO --}}

                        <div class="col-md-6">

                            <label class="form-label-custom">

                                Medicamento

                            </label>

                            <select
                                name="medicamento_id"
                                class="form-select form-control-custom"
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

                                            -
                                            {{ $medicamento->concentracion }}

                                        @endif

                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- DOSIS --}}

                        <div class="col-md-6">

                            <label class="form-label-custom">

                                Dosis

                            </label>

                            <input
                                type="text"
                                name="dosis"
                                value="{{ old('dosis') }}"
                                class="form-control form-control-custom"
                                placeholder="Ej. 500 mg - 1 tableta"
                                required
                            >

                        </div>


                        {{-- FRECUENCIA --}}

                        <div class="col-md-6">

                            <label class="form-label-custom">

                                Frecuencia

                            </label>

                            <input
                                type="text"
                                name="frecuencia"
                                value="{{ old('frecuencia') }}"
                                class="form-control form-control-custom"
                                placeholder="Ej. Cada 8 horas / 1 vez al día"
                                required
                            >

                        </div>


                        {{-- VÍA --}}

                        <div class="col-md-6">

                            <label class="form-label-custom">

                                Vía de administración

                            </label>

                            <select
                                name="via_administracion"
                                class="form-select form-control-custom"
                            >

                                <option
                                    value="VO"
                                    {{ old('via_administracion', 'VO') === 'VO' ? 'selected' : '' }}
                                >

                                    VO - Vía oral

                                </option>

                                <option
                                    value="IV"
                                    {{ old('via_administracion') === 'IV' ? 'selected' : '' }}
                                >

                                    IV - Intravenosa

                                </option>

                                <option
                                    value="IM"
                                    {{ old('via_administracion') === 'IM' ? 'selected' : '' }}
                                >

                                    IM - Intramuscular

                                </option>

                                <option
                                    value="SC"
                                    {{ old('via_administracion') === 'SC' ? 'selected' : '' }}
                                >

                                    SC - Subcutánea

                                </option>

                                <option
                                    value="SL"
                                    {{ old('via_administracion') === 'SL' ? 'selected' : '' }}
                                >

                                    SL - Sublingual

                                </option>

                                <option
                                    value="Otro"
                                    {{ old('via_administracion') === 'Otro' ? 'selected' : '' }}
                                >

                                    Otra

                                </option>

                            </select>

                        </div>


                        {{-- TM --}}

                        <div class="col-md-4">

                            <label class="form-label-custom">

                                TM - Mañana

                            </label>

                            <input
                                type="time"
                                name="hora_tm"
                                value="{{ old('hora_tm') }}"
                                class="form-control form-control-custom"
                            >

                        </div>


                        {{-- TT --}}

                        <div class="col-md-4">

                            <label class="form-label-custom">

                                TT - Tarde

                            </label>

                            <input
                                type="time"
                                name="hora_tt"
                                value="{{ old('hora_tt') }}"
                                class="form-control form-control-custom"
                            >

                        </div>


                        {{-- TN --}}

                        <div class="col-md-4">

                            <label class="form-label-custom">

                                TN - Noche

                            </label>

                            <input
                                type="time"
                                name="hora_tn"
                                value="{{ old('hora_tn') }}"
                                class="form-control form-control-custom"
                            >

                        </div>


                        {{-- FECHA INICIO --}}

                        <div class="col-md-6">

                            <label class="form-label-custom">

                                Fecha de inicio

                            </label>

                            <input
                                type="date"
                                name="fecha_inicio"
                                value="{{ old(
                                    'fecha_inicio',
                                    now()->format('Y-m-d')
                                ) }}"
                                class="form-control form-control-custom"
                                required
                            >

                        </div>


                        {{-- FECHA FIN --}}

                        <div class="col-md-6">

                            <label class="form-label-custom">

                                Fecha D/C

                            </label>

                            <input
                                type="date"
                                name="fecha_fin"
                                value="{{ old('fecha_fin') }}"
                                class="form-control form-control-custom"
                            >

                        </div>


                        {{-- ESTADO --}}

                        <div class="col-md-6">

                            <label class="form-label-custom">

                                Estado

                            </label>

                            <select
                                name="estado"
                                class="form-select form-control-custom"
                                required
                            >

                                <option
                                    value="activo"
                                    {{ old('estado', 'activo') === 'activo' ? 'selected' : '' }}
                                >

                                    Activo

                                </option>

                                <option
                                    value="suspendido"
                                    {{ old('estado') === 'suspendido' ? 'selected' : '' }}
                                >

                                    Suspendido

                                </option>

                                <option
                                    value="finalizado"
                                    {{ old('estado') === 'finalizado' ? 'selected' : '' }}
                                >

                                    Finalizado

                                </option>

                            </select>

                        </div>


                        {{-- INDICACIONES --}}

                        <div class="col-12">

                            <label class="form-label-custom">

                                Indicaciones

                            </label>

                            <textarea
                                name="indicaciones"
                                rows="3"
                                class="form-control form-control-custom"
                                placeholder="Indicaciones del tratamiento..."
                            >{{ old('indicaciones') }}</textarea>

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

                        Guardar tratamiento

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- ====================================================== --}}
{{-- MODALES EDITAR TRATAMIENTO --}}
{{-- ====================================================== --}}

@foreach($paciente->tratamientos as $tratamiento)

    <div
        class="modal fade"
        id="editarTratamiento{{ $tratamiento->id }}"
        tabindex="-1"
        aria-hidden="true"
    >

        <div class="modal-dialog modal-lg modal-dialog-centered">

            <div class="modal-content">

                <form
                    action="{{ route(
                        'tratamientos_kardex.update',
                        [
                            'paciente' => $paciente->id,
                            'tratamiento' => $tratamiento->id
                        ]
                    ) }}"
                    method="POST"
                >

                    @csrf

                    @method('PUT')

                    <div class="modal-header">

                        <div>

                            <h5 class="modal-title">
                                Editar tratamiento
                            </h5>

                            <small class="text-secondary">

                                Modifica la indicación registrada en la hoja de tratamientos.

                            </small>

                        </div>

                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                        ></button>

                    </div>


                    <div class="modal-body">

                        <div class="row g-3">

                            {{-- MEDICAMENTO --}}

                            <div class="col-md-6">

                                <label class="form-label-custom">

                                    Medicamento

                                </label>

                                <select
                                    name="medicamento_id"
                                    class="form-select form-control-custom"
                                    required
                                >

                                    <option value="">

                                        Seleccionar medicamento

                                    </option>

                                    @foreach($medicamentos as $medicamento)

                                        <option
                                            value="{{ $medicamento->id }}"
                                            {{ $tratamiento->medicamento_id == $medicamento->id ? 'selected' : '' }}
                                        >

                                            {{ $medicamento->nombre }}

                                            @if($medicamento->concentracion)

                                                -
                                                {{ $medicamento->concentracion }}

                                            @endif

                                        </option>

                                    @endforeach

                                </select>

                            </div>


                            {{-- DOSIS --}}

                            <div class="col-md-6">

                                <label class="form-label-custom">

                                    Dosis

                                </label>

                                <input
                                    type="text"
                                    name="dosis"
                                    value="{{ $tratamiento->dosis }}"
                                    class="form-control form-control-custom"
                                    placeholder="Ej. 500 mg - 1 tableta"
                                    required
                                >

                            </div>


                            {{-- FRECUENCIA --}}

                            <div class="col-md-6">

                                <label class="form-label-custom">

                                    Frecuencia

                                </label>

                                <input
                                    type="text"
                                    name="frecuencia"
                                    value="{{ $tratamiento->frecuencia }}"
                                    class="form-control form-control-custom"
                                    placeholder="Ej. Cada 8 horas / 1 vez al día"
                                    required
                                >

                            </div>


                            {{-- VÍA --}}

                            <div class="col-md-6">

                                <label class="form-label-custom">

                                    Vía de administración

                                </label>

                                <select
                                    name="via_administracion"
                                    class="form-select form-control-custom"
                                >

                                    <option
                                        value="VO"
                                        {{ $tratamiento->via_administracion === 'VO' ? 'selected' : '' }}
                                    >

                                        VO - Vía oral

                                    </option>

                                    <option
                                        value="IV"
                                        {{ $tratamiento->via_administracion === 'IV' ? 'selected' : '' }}
                                    >

                                        IV - Intravenosa

                                    </option>

                                    <option
                                        value="IM"
                                        {{ $tratamiento->via_administracion === 'IM' ? 'selected' : '' }}
                                    >

                                        IM - Intramuscular

                                    </option>

                                    <option
                                        value="SC"
                                        {{ $tratamiento->via_administracion === 'SC' ? 'selected' : '' }}
                                    >

                                        SC - Subcutánea

                                    </option>

                                    <option
                                        value="SL"
                                        {{ $tratamiento->via_administracion === 'SL' ? 'selected' : '' }}
                                    >

                                        SL - Sublingual

                                    </option>

                                    <option
                                        value="Otro"
                                        {{ $tratamiento->via_administracion === 'Otro' ? 'selected' : '' }}
                                    >

                                        Otra

                                    </option>

                                </select>

                            </div>


                            {{-- TM --}}

                            <div class="col-md-4">

                                <label class="form-label-custom">

                                    TM - Mañana

                                </label>

                                <input
                                    type="time"
                                    name="hora_tm"
                                    value="{{ $tratamiento->hora_tm
                                        ? $tratamiento->hora_tm->format('H:i')
                                        : ''
                                    }}"
                                    class="form-control form-control-custom"
                                >

                            </div>


                            {{-- TT --}}

                            <div class="col-md-4">

                                <label class="form-label-custom">

                                    TT - Tarde

                                </label>

                                <input
                                    type="time"
                                    name="hora_tt"
                                    value="{{ $tratamiento->hora_tt
                                        ? $tratamiento->hora_tt->format('H:i')
                                        : ''
                                    }}"
                                    class="form-control form-control-custom"
                                >

                            </div>


                            {{-- TN --}}

                            <div class="col-md-4">

                                <label class="form-label-custom">

                                    TN - Noche

                                </label>

                                <input
                                    type="time"
                                    name="hora_tn"
                                    value="{{ $tratamiento->hora_tn
                                        ? $tratamiento->hora_tn->format('H:i')
                                        : ''
                                    }}"
                                    class="form-control form-control-custom"
                                >

                            </div>


                            {{-- FECHA INICIO --}}

                            <div class="col-md-6">

                                <label class="form-label-custom">

                                    Fecha de inicio

                                </label>

                                <input
                                    type="date"
                                    name="fecha_inicio"
                                    value="{{ $tratamiento->fecha_inicio
                                        ? $tratamiento->fecha_inicio->format('Y-m-d')
                                        : ''
                                    }}"
                                    class="form-control form-control-custom"
                                    required
                                >

                            </div>


                            {{-- FECHA FIN --}}

                            <div class="col-md-6">

                                <label class="form-label-custom">

                                    Fecha D/C

                                </label>

                                <input
                                    type="date"
                                    name="fecha_fin"
                                    value="{{ $tratamiento->fecha_fin
                                        ? $tratamiento->fecha_fin->format('Y-m-d')
                                        : ''
                                    }}"
                                    class="form-control form-control-custom"
                                >

                            </div>


                            {{-- ESTADO --}}

                            <div class="col-md-6">

                                <label class="form-label-custom">

                                    Estado

                                </label>

                                <select
                                    name="estado"
                                    class="form-select form-control-custom"
                                    required
                                >

                                    <option
                                        value="activo"
                                        {{ $tratamiento->estado === 'activo' ? 'selected' : '' }}
                                    >

                                        Activo

                                    </option>

                                    <option
                                        value="suspendido"
                                        {{ $tratamiento->estado === 'suspendido' ? 'selected' : '' }}
                                    >

                                        Suspendido

                                    </option>

                                    <option
                                        value="finalizado"
                                        {{ $tratamiento->estado === 'finalizado' ? 'selected' : '' }}
                                    >

                                        Finalizado

                                    </option>

                                </select>

                            </div>


                            {{-- INDICACIONES --}}

                            <div class="col-12">

                                <label class="form-label-custom">

                                    Indicaciones

                                </label>

                                <textarea
                                    name="indicaciones"
                                    rows="3"
                                    class="form-control form-control-custom"
                                    placeholder="Indicaciones del tratamiento..."
                                >{{ $tratamiento->indicaciones }}</textarea>

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
                            class="btn btn-edit-save"
                        >

                            <i class="bi bi-pencil-square me-1"></i>

                            Guardar cambios

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endforeach


{{-- ====================================================== --}}
{{-- MODALES FINALIZAR TRATAMIENTO --}}
{{-- ====================================================== --}}

@foreach($paciente->tratamientos as $tratamiento)

    @if($tratamiento->estado !== 'finalizado')

        <div
            class="modal fade"
            id="finalizarTratamiento{{ $tratamiento->id }}"
            tabindex="-1"
            aria-hidden="true"
        >

            <div class="modal-dialog modal-dialog-centered">

                <div class="modal-content">

                    <div class="modal-header">

                        <h5 class="modal-title">

                            Finalizar tratamiento

                        </h5>

                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                        ></button>

                    </div>


                    <div class="modal-body">

                        <p class="mb-0">

                            ¿Estás seguro de finalizar el tratamiento de

                            <strong>

                                {{ $tratamiento->medicamento->nombre
                                    ?? 'este medicamento'
                                }}

                            </strong>?

                        </p>

                        <p class="text-secondary small mt-2 mb-0">

                            El tratamiento no será eliminado. Se conservará
                            en el Kardex como parte del historial del paciente.

                        </p>

                    </div>


                    <div class="modal-footer">

                        <button
                            type="button"
                            class="btn btn-light"
                            data-bs-dismiss="modal"
                        >

                            Cancelar

                        </button>


                        <form
                            action="{{ route(
                                'tratamientos_kardex.finalizar',
                                [
                                    'paciente' => $paciente->id,
                                    'tratamiento' => $tratamiento->id
                                ]
                            ) }}"
                            method="POST"
                        >

                            @csrf

                            @method('PATCH')

                            <button
                                type="submit"
                                class="btn btn-danger"
                            >

                                <i class="bi bi-check-circle me-1"></i>

                                Finalizar

                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    @endif

@endforeach


@endsection

