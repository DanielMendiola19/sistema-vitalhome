@extends('layouts.app')

@section('title', $paciente->nombre . ' ' . $paciente->apellido)

@section('content')

<style>
    /* =========================================================
       PÁGINA
    ========================================================= */

    .patient-detail-page {
        background: #f8fafc;
        min-height: calc(100vh - 70px);
        padding: 30px;
    }

    .patient-container {
        max-width: 1400px;
        margin: 0 auto;
    }

    /* =========================================================
       VOLVER
    ========================================================= */

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        color: #64748b;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        margin-bottom: 20px;
        transition: .2s ease;
    }

    .back-link:hover {
        color: #0f9d58;
    }

    /* =========================================================
       HEADER PACIENTE
    ========================================================= */

    .patient-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 24px;
    }

    .patient-header-left {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .patient-avatar {
        width: 54px;
        height: 54px;
        border-radius: 12px;
        background: #e8f5ee;
        color: #0f9d58;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        font-weight: 700;
        flex-shrink: 0;
    }

    .patient-header-title {
        color: #0f2d6b;
        font-size: 28px;
        font-weight: 700;
        margin: 0;
        line-height: 1.2;
    }

    .patient-header-subtitle {
        color: #64748b;
        margin: 5px 0 0;
        font-size: 13px;
    }

    .edit-button {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: white;
        color: #334155;
        border: 1px solid #e2e8f0;
        padding: 10px 15px;
        border-radius: 9px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: .2s ease;
    }

    .edit-button:hover {
        color: #0f9d58;
        border-color: #a7f3d0;
        background: #f0fdf4;
    }

    /* =========================================================
       ALERTAS
    ========================================================= */

    .success-message {
        background: #ecfdf3;
        color: #15803d;
        border: 1px solid #bbf7d0;
        border-radius: 10px;
    }

    /* =========================================================
       RESUMEN
    ========================================================= */

    .summary-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        margin-bottom: 22px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(15, 23, 42, .04);
    }

    .summary-item {
        padding: 18px 20px;
        border-right: 1px solid #f1f5f9;
    }

    .summary-item:last-child {
        border-right: none;
    }

    .summary-label {
        color: #94a3b8;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: .4px;
        margin-bottom: 7px;
    }

    .summary-value {
        color: #0f172a;
        font-size: 14px;
        font-weight: 700;
    }

    .summary-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #dcfce7;
        color: #15803d;
        border-radius: 999px;
        padding: 5px 10px;
        font-size: 11px;
        font-weight: 700;
    }

    .summary-status::before {
        content: '';
        width: 6px;
        height: 6px;
        background: #22c55e;
        border-radius: 50%;
    }

    .summary-status.inactive {
        background: #f1f5f9;
        color: #64748b;
    }

    .summary-status.inactive::before {
        background: #94a3b8;
    }

    /* =========================================================
       TABS
    ========================================================= */

    .detail-tabs {
        display: flex;
        gap: 4px;
        background: white;
        border: 1px solid #e2e8f0;
        padding: 5px;
        border-radius: 10px;
        margin-bottom: 20px;
        overflow-x: auto;
        box-shadow: 0 1px 4px rgba(15, 23, 42, .03);
    }

    .detail-tab {
        border: none;
        background: transparent;
        color: #64748b;
        padding: 10px 17px;
        border-radius: 7px;
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
        transition: .2s ease;
    }

    .detail-tab:hover {
        background: #f8fafc;
        color: #0f2d6b;
    }

    .detail-tab.active {
        background: #0f2d6b;
        color: white;
    }

    .tab-panel {
        display: none;
    }

    .tab-panel.active {
        display: block;
    }

    /* =========================================================
       TARJETAS GENERALES
    ========================================================= */

    .detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .detail-card,
    .main-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(15, 23, 42, .04);
        overflow: hidden;
    }

    .detail-card-header {
        padding: 18px 20px;
        border-bottom: 1px solid #f1f5f9;
    }

    .detail-card-title {
        color: #0f2d6b;
        font-size: 16px;
        font-weight: 700;
        margin: 0;
    }

    .detail-card-subtitle {
        color: #94a3b8;
        font-size: 12px;
        margin: 5px 0 0;
    }

    /* =========================================================
       DATOS BÁSICOS
    ========================================================= */

    .basic-row {
        display: flex;
        justify-content: space-between;
        gap: 20px;
        padding: 13px 20px;
        border-bottom: 1px solid #f1f5f9;
    }

    .basic-row:last-child {
        border-bottom: none;
    }

    .basic-label {
        color: #64748b;
        font-size: 13px;
    }

    .basic-value {
        color: #1e293b;
        font-size: 13px;
        font-weight: 600;
        text-align: right;
    }

    /* =========================================================
       ANTECEDENTES
    ========================================================= */

    .antecedents {
        padding: 20px;
        margin: 0;
        list-style: none;
    }

    .antecedent-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 9px;
        padding: 15px;
        color: #475569;
        font-size: 13px;
        line-height: 1.6;
    }

    /* =========================================================
       KARDEX
    ========================================================= */

    .main-card-body {
        padding: 20px;
    }

    .medication-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        padding: 16px 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .medication-row:first-child {
        padding-top: 0;
    }

    .medication-row:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .medication-name {
        color: #111827;
        font-size: 14px;
        font-weight: 700;
    }

    .medication-info {
        color: #64748b;
        font-size: 12px;
        margin-top: 4px;
    }

    .medication-dose {
        color: #0f9d58;
        background: #ecfdf3;
        padding: 7px 10px;
        border-radius: 7px;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
    }

    /* =========================================================
       HISTORIAL CRONOLÓGICO
    ========================================================= */

    .history-container {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(15, 23, 42, .04);
        overflow: hidden;
    }

    .history-header {
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 17px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
    }

    .history-header-left {
        display: flex;
        align-items: center;
        gap: 11px;
    }

    .history-header-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: #e8f5ee;
        color: #0f9d58;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 17px;
    }

    .history-header-title {
        color: #0f2d6b;
        font-size: 16px;
        font-weight: 700;
        margin: 0;
    }

    .history-header-subtitle {
        color: #64748b;
        font-size: 11px;
        margin: 3px 0 0;
    }

    .add-observation-button {
        width: 38px;
        height: 38px;
        border: none;
        border-radius: 8px;
        background: #0f9d58;
        color: white;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 19px;
        transition: .2s ease;
        box-shadow: 0 2px 5px rgba(15, 157, 88, .2);
    }

    .add-observation-button:hover {
        background: #087f45;
        transform: translateY(-1px);
    }

    .history-body {
        padding: 18px 20px;
    }

    .history-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .history-entry {
        border: 1px solid #e2e8f0;
        border-radius: 9px;
        background: white;
        padding: 15px 16px;
        transition: .2s ease;
    }

    .history-entry:hover {
        border-color: #cbd5e1;
        box-shadow: 0 2px 7px rgba(15, 23, 42, .04);
    }

    .history-entry-date {
        color: #0f9d58;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 6px;
    }

    .history-entry-title {
        color: #111827;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 5px;
        line-height: 1.4;
    }

    .history-entry-description {
        color: #475569;
        font-size: 13px;
        line-height: 1.6;
        margin-bottom: 7px;
        white-space: pre-line;
    }

    .history-entry-origin {
        color: #64748b;
        font-size: 12px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .history-entry-origin i {
        color: #94a3b8;
    }

    .history-empty {
        text-align: center;
        padding: 45px 20px;
        color: #64748b;
    }

    .history-empty-icon {
        width: 54px;
        height: 54px;
        margin: 0 auto 14px;
        border-radius: 50%;
        background: #f1f5f9;
        color: #94a3b8;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
    }

    .history-empty-title {
        color: #334155;
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .history-empty-text {
        color: #94a3b8;
        font-size: 12px;
        margin: 0;
    }

    /* =========================================================
       INFORMACIÓN DEL REGISTRO
    ========================================================= */

    .record-info {
        margin-top: 18px;
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(15, 23, 42, .03);
    }

    .record-info-header {
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 14px 18px;
        color: #0f2d6b;
        font-size: 14px;
        font-weight: 700;
    }

    /* =========================================================
       SIGNOS VITALES
    ========================================================= */

    .vitals-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        margin-bottom: 18px;
    }

    .vitals-table-container {
        border: 1px solid #e2e8f0;
        border-radius: 9px;
        overflow: hidden;
    }

    .vitals-table {
        margin: 0;
    }

    .vitals-table thead th {
        background: #f8fafc;
        color: #64748b;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: .3px;
        border-bottom: 1px solid #e2e8f0;
        padding: 12px 14px;
        white-space: nowrap;
    }

    .vitals-table tbody td {
        padding: 13px 14px;
        border-bottom: 1px solid #f1f5f9;
        color: #1e293b;
        font-size: 13px;
    }

    .vitals-table tbody tr:last-child td {
        border-bottom: none;
    }

    .vital-value {
        font-weight: 700;
        color: #111827;
    }

    .vital-unit {
        color: #94a3b8;
        font-size: 11px;
        margin-left: 3px;
    }

    .vitals-placeholder {
        text-align: center;
        padding: 55px 20px;
        color: #64748b;
    }

    .vitals-placeholder-icon {
        width: 58px;
        height: 58px;
        margin: 0 auto 15px;
        background: #f1f5f9;
        color: #94a3b8;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }

    /* =========================================================
       MODALES
    ========================================================= */

    .custom-modal .modal-content,
    .edit-modal .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 15px 40px rgba(15, 23, 42, .15);
        overflow: hidden;
    }

    .custom-modal .modal-header,
    .edit-modal .modal-header {
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 17px 20px;
    }

    .custom-modal .modal-title,
    .edit-modal .modal-title {
        color: #0f2d6b;
        font-size: 17px;
        font-weight: 700;
    }

    .custom-modal .modal-body,
    .edit-modal .modal-body {
        padding: 20px;
    }

    .custom-modal .modal-footer,
    .edit-modal .modal-footer {
        border-top: 1px solid #e2e8f0;
        padding: 13px 20px;
        background: #fff;
    }

    .observation-modal-icon {
        width: 38px;
        height: 38px;
        border-radius: 8px;
        background: #e8f5ee;
        color: #0f9d58;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-right: 10px;
    }

    .observation-modal-info {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 11px 13px;
        margin-bottom: 16px;
        color: #64748b;
        font-size: 12px;
    }

    .observation-modal-info strong {
        color: #334155;
    }

    .observation-textarea {
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        min-height: 130px;
        resize: vertical;
        font-size: 13px;
        line-height: 1.6;
    }

    .observation-textarea:focus {
        border-color: #0f9d58;
        box-shadow: 0 0 0 3px rgba(15, 157, 88, .1);
    }

    /* =========================================================
       BOTONES
    ========================================================= */

    .btn-success {
        background: #0f9d58;
        border-color: #0f9d58;
    }

    .btn-success:hover {
        background: #087f45;
        border-color: #087f45;
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media(max-width: 1100px) {

        .summary-card {
            grid-template-columns: repeat(3, 1fr);
        }

        .summary-item:nth-child(3) {
            border-right: none;
        }

        .detail-grid {
            grid-template-columns: 1fr;
        }
    }

    @media(max-width: 800px) {

        .patient-detail-page {
            padding: 20px 15px;
        }

        .patient-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .summary-card {
            grid-template-columns: repeat(2, 1fr);
        }

        .summary-item:nth-child(3) {
            border-right: 1px solid #f1f5f9;
        }

        .summary-item:nth-child(even) {
            border-right: none;
        }

        .vitals-header {
            align-items: flex-start;
            flex-direction: column;
        }
    }

    @media(max-width: 500px) {

        .summary-card {
            grid-template-columns: 1fr;
        }

        .summary-item {
            border-right: none !important;
        }

        .patient-header-left {
            align-items: flex-start;
        }

        .patient-header-title {
            font-size: 23px;
        }

        .basic-row {
            flex-direction: column;
            gap: 5px;
        }

        .basic-value {
            text-align: left;
        }

        .history-header {
            align-items: flex-start;
        }

        .medication-row {
            align-items: flex-start;
            flex-direction: column;
        }
    }
</style>


<div class="patient-detail-page">

    <div class="patient-container">

        {{-- =====================================================
             VOLVER
        ====================================================== --}}

        <a
            href="{{ route('pacientes.index') }}"
            class="back-link"
        >
            <i class="bi bi-arrow-left"></i>
            Volver a pacientes
        </a>


        {{-- =====================================================
             MENSAJES
        ====================================================== --}}

        @if(session('success'))

            <div class="alert success-message mb-4">

                <i class="bi bi-check-circle me-2"></i>

                {{ session('success') }}

            </div>

        @endif


        @if($errors->any())

            <div class="alert alert-danger border-0 mb-4">

                <i class="bi bi-exclamation-circle me-2"></i>

                <strong>No se pudo guardar la información.</strong>

                <ul class="mb-0 mt-2">

                    @foreach($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif


        {{-- =====================================================
             HEADER DEL PACIENTE
        ====================================================== --}}

        <div class="patient-header">

            <div class="patient-header-left">

                <div class="patient-avatar">

                    {{ strtoupper(substr($paciente->nombre, 0, 1)) }}

                </div>

                <div>

                    <h1 class="patient-header-title">

                        {{ $paciente->nombre }}
                        {{ $paciente->apellido }}

                    </h1>

                    <p class="patient-header-subtitle">

                        CI: {{ $paciente->ci }}

                    </p>

                </div>

            </div>


            <button
                type="button"
                class="edit-button"
                data-bs-toggle="modal"
                data-bs-target="#editarPacienteModal"
            >

                <i class="bi bi-pencil"></i>

                Editar información

            </button>

        </div>


        {{-- =====================================================
             RESUMEN
        ====================================================== --}}

        <div class="summary-card">

            <div class="summary-item">

                <div class="summary-label">
                    NOMBRE
                </div>

                <div class="summary-value">
                    {{ $paciente->nombre }} {{ $paciente->apellido }}
                </div>

            </div>


            <div class="summary-item">

                <div class="summary-label">
                    EDAD
                </div>

                <div class="summary-value">

                    {{ $paciente->fecha_nacimiento->age }} años

                </div>

            </div>


            <div class="summary-item">

                <div class="summary-label">
                    SEXO
                </div>

                <div class="summary-value">
                    {{ $paciente->sexo }}
                </div>

            </div>


            <div class="summary-item">

                <div class="summary-label">
                    CI
                </div>

                <div class="summary-value">
                    {{ $paciente->ci }}
                </div>

            </div>


            <div class="summary-item">

                <div class="summary-label">
                    ESTADO
                </div>

                <div class="summary-value">

                    @if($paciente->tratamientos->where('estado', 'activo')->count())

                        <span class="summary-status">
                            Activo
                        </span>

                    @else

                        <span class="summary-status inactive">
                            Inactivo
                        </span>

                    @endif

                </div>

            </div>


            <div class="summary-item">

                <div class="summary-label">
                    REGISTRADO
                </div>

                <div class="summary-value">

                    {{ $paciente->created_at->format('d/m/Y') }}

                </div>

            </div>

        </div>


        {{-- =====================================================
             TABS
        ====================================================== --}}

        <div class="detail-tabs">

            <button
                type="button"
                class="detail-tab active"
                data-tab="informacion"
            >
                <i class="bi bi-person-vcard me-1"></i>
                Información general
            </button>


            <button
                type="button"
                class="detail-tab"
                data-tab="kardex"
            >
                <i class="bi bi-capsule me-1"></i>
                Kardex
            </button>


            <button
                type="button"
                class="detail-tab"
                data-tab="historial"
            >
                <i class="bi bi-clock-history me-1"></i>
                Historial
            </button>


            <button
                type="button"
                class="detail-tab"
                data-tab="signos"
            >
                <i class="bi bi-heart-pulse me-1"></i>
                Signos vitales
            </button>

        </div>


        {{-- =====================================================
             INFORMACIÓN GENERAL
        ====================================================== --}}

        <div
            id="tab-informacion"
            class="tab-panel active"
        >

            <div class="detail-grid">

                {{-- DATOS BÁSICOS --}}

                <div class="detail-card">

                    <div class="detail-card-header">

                        <h2 class="detail-card-title">
                            Datos básicos
                        </h2>

                    </div>


                    <div class="basic-row">

                        <span class="basic-label">
                            Código interno
                        </span>

                        <span class="basic-value">
                            P{{ $paciente->id }}
                        </span>

                    </div>


                    <div class="basic-row">

                        <span class="basic-label">
                            Nombre completo
                        </span>

                        <span class="basic-value">
                            {{ $paciente->nombre }}
                            {{ $paciente->apellido }}
                        </span>

                    </div>


                    <div class="basic-row">

                        <span class="basic-label">
                            Carnet de identidad
                        </span>

                        <span class="basic-value">
                            {{ $paciente->ci }}
                        </span>

                    </div>


                    <div class="basic-row">

                        <span class="basic-label">
                            Sexo
                        </span>

                        <span class="basic-value">
                            {{ $paciente->sexo }}
                        </span>

                    </div>


                    <div class="basic-row">

                        <span class="basic-label">
                            Edad
                        </span>

                        <span class="basic-value">
                            {{ $paciente->fecha_nacimiento->age }} años
                        </span>

                    </div>


                    <div class="basic-row">

                        <span class="basic-label">
                            Fecha de nacimiento
                        </span>

                        <span class="basic-value">
                            {{ $paciente->fecha_nacimiento->format('d/m/Y') }}
                        </span>

                    </div>


                    <div class="basic-row">

                        <span class="basic-label">
                            Teléfono
                        </span>

                        <span class="basic-value">
                            {{ $paciente->telefono ?: 'No registrado' }}
                        </span>

                    </div>


                    <div class="basic-row">

                        <span class="basic-label">
                            Dirección
                        </span>

                        <span class="basic-value">
                            {{ $paciente->direccion ?: 'No registrada' }}
                        </span>

                    </div>


                    <div class="basic-row">

                        <span class="basic-label">
                            Última actualización
                        </span>

                        <span class="basic-value">
                            {{ $paciente->updated_at->format('d/m/Y H:i') }}
                        </span>

                    </div>


                    <div class="basic-row">

                        <span class="basic-label">
                            Medicamentos activos
                        </span>

                        <span class="basic-value">
                            {{ $paciente->tratamientos->where('estado', 'activo')->count() }}
                        </span>

                    </div>

                </div>


                {{-- ANTECEDENTES --}}

                <div class="detail-card">

                    <div class="detail-card-header">

                        <h2 class="detail-card-title">
                            Antecedentes
                        </h2>

                        <p class="detail-card-subtitle">
                            Información general registrada del paciente
                        </p>

                    </div>


                    <div class="antecedents">

                        @if($paciente->observaciones)

                            <div class="antecedent-box">

                                <i class="bi bi-info-circle me-1"></i>

                                {{ $paciente->observaciones }}

                            </div>

                        @else

                            <div class="antecedent-box">

                                No existen observaciones generales registradas.

                            </div>

                        @endif

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             KARDEX
        ====================================================== --}}

        <div
            id="tab-kardex"
            class="tab-panel"
        >

            <div class="main-card">

                <div class="detail-card-header">

                    <h2 class="detail-card-title">
                        Medicamentos y tratamientos
                    </h2>

                    <p class="detail-card-subtitle">
                        Tratamientos registrados actualmente para el paciente
                    </p>

                </div>


                <div class="main-card-body">

                    @forelse($paciente->tratamientos as $tratamiento)

                        <div class="medication-row">

                            <div>

                                <div class="medication-name">

                                    {{ $tratamiento->medicamento->nombre ?? 'Medicamento no disponible' }}

                                </div>

                                <div class="medication-info">

                                    {{ $tratamiento->frecuencia }}

                                    @if($tratamiento->via_administracion)

                                        · {{ $tratamiento->via_administracion }}

                                    @endif

                                    · Inicio:

                                    {{ $tratamiento->fecha_inicio->format('d/m/Y') }}

                                </div>

                            </div>


                            <div class="medication-dose">

                                {{ $tratamiento->dosis }}

                            </div>

                        </div>

                    @empty

                        <div class="vitals-placeholder">

                            <div class="vitals-placeholder-icon">

                                <i class="bi bi-capsule"></i>

                            </div>

                            <strong>
                                No existen tratamientos registrados
                            </strong>

                            <p class="mt-2 mb-0">
                                Este paciente todavía no tiene medicamentos registrados.
                            </p>

                        </div>

                    @endforelse

                </div>

            </div>

        </div>


        {{-- =====================================================
             HISTORIAL CRONOLÓGICO
        ====================================================== --}}

        <div
            id="tab-historial"
            class="tab-panel"
        >

            <div class="history-container">

                {{-- CABECERA --}}

                <div class="history-header">

                    <div class="history-header-left">

                        <div class="history-header-icon">

                            <i class="bi bi-clock-history"></i>

                        </div>

                        <div>

                            <h2 class="history-header-title">
                                Historial cronológico
                            </h2>

                            <p class="history-header-subtitle">
                                Registro de observaciones realizadas al paciente
                            </p>

                        </div>

                    </div>


                    {{-- BOTÓN + --}}

                    <button
                        type="button"
                        class="add-observation-button"
                        data-bs-toggle="modal"
                        data-bs-target="#nuevaObservacionModal"
                        title="Agregar observación"
                    >

                        <i class="bi bi-plus-lg"></i>

                    </button>

                </div>


                {{-- CUERPO DEL HISTORIAL --}}

                <div class="history-body">

                    @php

                        $observacionesOrdenadas = $paciente
                            ->observacionesClinicas
                            ->sortByDesc('created_at');

                    @endphp


                    @if($observacionesOrdenadas->count())

                        <div class="history-list">

                            @foreach($observacionesOrdenadas as $observacion)

                                <div class="history-entry">

                                    {{-- FECHA / HORA --}}

                                    <div class="history-entry-date">

                                        <i class="bi bi-calendar3 me-1"></i>

                                        {{ $observacion->created_at->format('d/m/Y H:i') }}

                                    </div>


                                    {{-- TÍTULO --}}

                                    <div class="history-entry-title">

                                        Observación clínica

                                    </div>


                                    {{-- DESCRIPCIÓN --}}

                                    <div class="history-entry-description">

                                        {{ $observacion->observacion }}

                                    </div>


                                    {{-- USUARIO / ROL --}}

                                    <div class="history-entry-origin">

                                        <i class="bi bi-person-circle"></i>

                                        @if($observacion->usuario)

                                            {{ $observacion->usuario->nombre }}
                                            {{ $observacion->usuario->apellido }}

                                            @if($observacion->usuario->rol)

                                                · {{ ucfirst($observacion->usuario->rol) }}

                                            @endif

                                        @else

                                            Usuario no disponible

                                        @endif

                                    </div>

                                </div>

                            @endforeach

                        </div>

                    @else

                        <div class="history-empty">

                            <div class="history-empty-icon">

                                <i class="bi bi-journal-text"></i>

                            </div>

                            <div class="history-empty-title">

                                No existen observaciones registradas

                            </div>

                            <p class="history-empty-text">

                                Utiliza el botón + para agregar la primera observación.

                            </p>

                        </div>

                    @endif

                </div>

            </div>


            {{-- INFORMACIÓN DEL REGISTRO --}}

            <div class="record-info">

                <div class="record-info-header">

                    <i class="bi bi-info-circle me-1"></i>

                    Información del registro

                </div>


                <div class="basic-row">

                    <span class="basic-label">
                        Registro creado
                    </span>

                    <span class="basic-value">
                        {{ $paciente->created_at->format('d/m/Y H:i') }}
                    </span>

                </div>


                <div class="basic-row">

                    <span class="basic-label">
                        Última modificación
                    </span>

                    <span class="basic-value">
                        {{ $paciente->updated_at->format('d/m/Y H:i') }}
                    </span>

                </div>


                <div class="basic-row">

                    <span class="basic-label">
                        Tratamientos registrados
                    </span>

                    <span class="basic-value">
                        {{ $paciente->tratamientos->count() }}
                    </span>

                </div>


                <div class="basic-row">

                    <span class="basic-label">
                        Observaciones adicionales
                    </span>

                    <span class="basic-value">
                        {{ $paciente->observacionesClinicas->count() }}
                    </span>

                </div>

            </div>

        </div>


        {{-- =====================================================
             SIGNOS VITALES
        ====================================================== --}}

        <div
            id="tab-signos"
            class="tab-panel"
        >

            <div class="main-card">

                <div class="main-card-body">

                    <div class="vitals-header">

                        <div>

                            <h2 class="detail-card-title mb-1">
                                Signos vitales
                            </h2>

                            <p class="detail-card-subtitle mb-0">
                                Registros recientes del paciente
                            </p>

                        </div>


                        <button
                            type="button"
                            class="btn btn-success"
                            data-bs-toggle="modal"
                            data-bs-target="#nuevoSignoVitalModal"
                        >

                            <i class="bi bi-plus-lg me-1"></i>

                            Registrar signos vitales

                        </button>

                    </div>


                    @if($paciente->signosVitales->count())

                        <div class="vitals-table-container">

                            <div class="table-responsive">

                                <table class="table vitals-table align-middle">

                                    <thead>

                                        <tr>

                                            <th>
                                                FECHA
                                            </th>

                                            <th>
                                                P/A
                                            </th>

                                            <th>
                                                FC
                                            </th>

                                            <th>
                                                FR
                                            </th>

                                            <th>
                                                TEMPERATURA
                                            </th>

                                            <th>
                                                SPO2
                                            </th>

                                        </tr>

                                    </thead>


                                    <tbody>

                                        @foreach($paciente->signosVitales->sortByDesc('fecha_registro') as $signo)

                                            <tr>

                                                <td>

                                                    <div class="fw-semibold text-dark">

                                                        {{ $signo->fecha_registro->format('d/m/Y') }}

                                                    </div>

                                                    <div class="text-secondary small">

                                                        {{ $signo->fecha_registro->format('H:i') }}

                                                    </div>

                                                </td>


                                                <td>

                                                    <span class="vital-value">

                                                        {{ $signo->presion_arterial }}

                                                    </span>

                                                    <span class="vital-unit">
                                                        mmHg
                                                    </span>

                                                </td>


                                                <td>

                                                    <span class="vital-value">

                                                        {{ $signo->frecuencia_cardiaca }}

                                                    </span>

                                                    <span class="vital-unit">
                                                        lpm
                                                    </span>

                                                </td>


                                                <td>

                                                    <span class="vital-value">

                                                        {{ $signo->frecuencia_respiratoria }}

                                                    </span>

                                                    <span class="vital-unit">
                                                        rpm
                                                    </span>

                                                </td>


                                                <td>

                                                    <span class="vital-value">

                                                        {{ number_format((float) $signo->temperatura, 1) }}

                                                    </span>

                                                    <span class="vital-unit">
                                                        °C
                                                    </span>

                                                </td>


                                                <td>

                                                    <span class="vital-value">

                                                        {{ $signo->spo2 }}

                                                    </span>

                                                    <span class="vital-unit">
                                                        %
                                                    </span>

                                                </td>

                                            </tr>

                                        @endforeach

                                    </tbody>

                                </table>

                            </div>

                        </div>

                    @else

                        <div class="vitals-placeholder">

                            <div class="vitals-placeholder-icon">

                                <i class="bi bi-heart-pulse"></i>

                            </div>

                            <strong>
                                No existen registros de signos vitales
                            </strong>

                            <p class="mt-2 mb-3">

                                Registra los signos vitales del paciente para comenzar
                                a construir su historial.

                            </p>

                            <button
                                type="button"
                                class="btn btn-success"
                                data-bs-toggle="modal"
                                data-bs-target="#nuevoSignoVitalModal"
                            >

                                <i class="bi bi-plus-lg me-1"></i>

                                Registrar primer registro

                            </button>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>


{{-- =========================================================
     MODAL NUEVA OBSERVACIÓN
========================================================= --}}

<div
    class="modal fade custom-modal"
    id="nuevaObservacionModal"
    tabindex="-1"
    aria-labelledby="nuevaObservacionModalLabel"
    aria-hidden="true"
>

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <form
                method="POST"
                action="{{ route('pacientes.observaciones.store', $paciente->id) }}"
            >

                @csrf


                {{-- HEADER --}}

                <div class="modal-header">

                    <div class="d-flex align-items-center">

                        <div class="observation-modal-icon">

                            <i class="bi bi-plus-lg"></i>

                        </div>

                        <div>

                            <h5
                                class="modal-title mb-0"
                                id="nuevaObservacionModalLabel"
                            >

                                Agregar observación

                            </h5>

                            <small class="text-secondary">

                                {{ $paciente->nombre }}
                                {{ $paciente->apellido }}

                            </small>

                        </div>

                    </div>


                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Cerrar"
                    ></button>

                </div>


                {{-- BODY --}}

                <div class="modal-body">

                    <div class="observation-modal-info">

                        <i class="bi bi-info-circle me-1"></i>

                        La fecha, hora y usuario que realiza el registro
                        se asignarán automáticamente.

                    </div>


                    <div class="mb-3">

                        <label
                            for="observacion"
                            class="form-label fw-semibold"
                        >

                            Descripción de la observación

                        </label>


                        <textarea
                            name="observacion"
                            id="observacion"
                            class="form-control observation-textarea @error('observacion') is-invalid @enderror"
                            placeholder="Escribe aquí la observación del paciente..."
                            required
                        >{{ old('observacion') }}</textarea>


                        @error('observacion')

                            <div class="invalid-feedback">

                                {{ $message }}

                            </div>

                        @enderror

                    </div>

                </div>


                {{-- FOOTER --}}

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

                        <i class="bi bi-check2 me-1"></i>

                        Registrar observación

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- =========================================================
     MODAL NUEVOS SIGNOS VITALES
========================================================= --}}

<div
    class="modal fade custom-modal"
    id="nuevoSignoVitalModal"
    tabindex="-1"
    aria-labelledby="nuevoSignoVitalModalLabel"
    aria-hidden="true"
>

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <form
                method="POST"
                action="{{ route('pacientes.signos-vitales.store', $paciente->id) }}"
            >

                @csrf


                {{-- HEADER --}}

                <div class="modal-header">

                    <div>

                        <h5
                            class="modal-title"
                            id="nuevoSignoVitalModalLabel"
                        >

                            Registrar signos vitales

                        </h5>

                        <small class="text-secondary">

                            {{ $paciente->nombre }}
                            {{ $paciente->apellido }}

                        </small>

                    </div>


                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Cerrar"
                    ></button>

                </div>


                {{-- BODY --}}

                <div class="modal-body">

                    <div class="row g-3">

                        {{-- FECHA --}}

                        <div class="col-12">

                            <label
                                for="fecha_registro"
                                class="form-label fw-semibold"
                            >

                                Fecha y hora del registro

                            </label>

                            <input
                                type="datetime-local"
                                id="fecha_registro"
                                name="fecha_registro"
                                class="form-control @error('fecha_registro') is-invalid @enderror"
                                value="{{ old('fecha_registro', now()->format('Y-m-d\TH:i')) }}"
                                required
                            >

                            @error('fecha_registro')

                                <div class="invalid-feedback">

                                    {{ $message }}

                                </div>

                            @enderror

                        </div>


                        {{-- PRESIÓN --}}

                        <div class="col-md-6">

                            <label
                                for="presion_arterial"
                                class="form-label fw-semibold"
                            >

                                Presión arterial

                            </label>

                            <div class="input-group">

                                <input
                                    type="text"
                                    id="presion_arterial"
                                    name="presion_arterial"
                                    class="form-control @error('presion_arterial') is-invalid @enderror"
                                    placeholder="130/80"
                                    maxlength="20"
                                    value="{{ old('presion_arterial') }}"
                                    required
                                >

                                <span class="input-group-text">
                                    mmHg
                                </span>

                            </div>

                            @error('presion_arterial')

                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- FC --}}

                        <div class="col-md-6">

                            <label
                                for="frecuencia_cardiaca"
                                class="form-label fw-semibold"
                            >

                                Frecuencia cardíaca

                            </label>

                            <div class="input-group">

                                <input
                                    type="number"
                                    id="frecuencia_cardiaca"
                                    name="frecuencia_cardiaca"
                                    class="form-control @error('frecuencia_cardiaca') is-invalid @enderror"
                                    placeholder="74"
                                    min="1"
                                    max="300"
                                    value="{{ old('frecuencia_cardiaca') }}"
                                    required
                                >

                                <span class="input-group-text">
                                    lpm
                                </span>

                            </div>

                            @error('frecuencia_cardiaca')

                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- FR --}}

                        <div class="col-md-6">

                            <label
                                for="frecuencia_respiratoria"
                                class="form-label fw-semibold"
                            >

                                Frecuencia respiratoria

                            </label>

                            <div class="input-group">

                                <input
                                    type="number"
                                    id="frecuencia_respiratoria"
                                    name="frecuencia_respiratoria"
                                    class="form-control @error('frecuencia_respiratoria') is-invalid @enderror"
                                    placeholder="18"
                                    min="1"
                                    max="100"
                                    value="{{ old('frecuencia_respiratoria') }}"
                                    required
                                >

                                <span class="input-group-text">
                                    rpm
                                </span>

                            </div>

                            @error('frecuencia_respiratoria')

                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- TEMPERATURA --}}

                        <div class="col-md-6">

                            <label
                                for="temperatura"
                                class="form-label fw-semibold"
                            >

                                Temperatura

                            </label>

                            <div class="input-group">

                                <input
                                    type="number"
                                    id="temperatura"
                                    name="temperatura"
                                    class="form-control @error('temperatura') is-invalid @enderror"
                                    placeholder="36.5"
                                    min="25"
                                    max="45"
                                    step="0.1"
                                    value="{{ old('temperatura') }}"
                                    required
                                >

                                <span class="input-group-text">
                                    °C
                                </span>

                            </div>

                            @error('temperatura')

                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        {{-- SPO2 --}}

                        <div class="col-md-6">

                            <label
                                for="spo2"
                                class="form-label fw-semibold"
                            >

                                Saturación de oxígeno (SpO2)

                            </label>

                            <div class="input-group">

                                <input
                                    type="number"
                                    id="spo2"
                                    name="spo2"
                                    class="form-control @error('spo2') is-invalid @enderror"
                                    placeholder="96"
                                    min="0"
                                    max="100"
                                    value="{{ old('spo2') }}"
                                    required
                                >

                                <span class="input-group-text">
                                    %
                                </span>

                            </div>

                            @error('spo2')

                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>

                </div>


                {{-- FOOTER --}}

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

                        <i class="bi bi-check2 me-1"></i>

                        Guardar signos vitales

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- =========================================================
     MODAL EDITAR PACIENTE
========================================================= --}}

<div
    class="modal fade edit-modal"
    id="editarPacienteModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <form
                method="POST"
                action="{{ route('pacientes.update', $paciente->id) }}"
            >

                @csrf
                @method('PUT')


                <div class="modal-header">

                    <div>

                        <h5 class="modal-title">
                            Editar información
                        </h5>

                        <small class="text-secondary">

                            {{ $paciente->nombre }}
                            {{ $paciente->apellido }}

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

                        {{-- NOMBRE --}}

                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Nombre
                            </label>

                            <input
                                type="text"
                                name="nombre"
                                class="form-control"
                                value="{{ $paciente->nombre }}"
                                required
                            >

                        </div>


                        {{-- APELLIDO --}}

                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Apellido
                            </label>

                            <input
                                type="text"
                                name="apellido"
                                class="form-control"
                                value="{{ $paciente->apellido }}"
                                required
                            >

                        </div>


                        {{-- CI --}}

                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Carnet de identidad
                            </label>

                            <input
                                type="text"
                                name="ci"
                                class="form-control"
                                value="{{ $paciente->ci }}"
                                required
                            >

                        </div>


                        {{-- FECHA --}}

                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Fecha de nacimiento
                            </label>

                            <input
                                type="date"
                                name="fecha_nacimiento"
                                class="form-control"
                                value="{{ $paciente->fecha_nacimiento->format('Y-m-d') }}"
                                required
                            >

                        </div>


                        {{-- SEXO --}}

                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Sexo
                            </label>

                            <select
                                name="sexo"
                                class="form-select"
                                required
                            >

                                <option
                                    value="Femenino"
                                    {{ $paciente->sexo === 'Femenino' ? 'selected' : '' }}
                                >
                                    Femenino
                                </option>

                                <option
                                    value="Masculino"
                                    {{ $paciente->sexo === 'Masculino' ? 'selected' : '' }}
                                >
                                    Masculino
                                </option>

                            </select>

                        </div>


                        {{-- TELEFONO --}}

                        <div class="col-md-6">

                            <label class="form-label fw-semibold">
                                Teléfono
                            </label>

                            <input
                                type="text"
                                name="telefono"
                                class="form-control"
                                value="{{ $paciente->telefono }}"
                            >

                        </div>


                        {{-- DIRECCIÓN --}}

                        <div class="col-12">

                            <label class="form-label fw-semibold">
                                Dirección
                            </label>

                            <input
                                type="text"
                                name="direccion"
                                class="form-control"
                                value="{{ $paciente->direccion }}"
                            >

                        </div>


                        {{-- OBSERVACIONES GENERALES --}}

                        <div class="col-12">

                            <label class="form-label fw-semibold">
                                Observaciones generales
                            </label>

                            <textarea
                                name="observaciones"
                                rows="4"
                                class="form-control"
                            >{{ $paciente->observaciones }}</textarea>

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

                        <i class="bi bi-check2 me-1"></i>

                        Guardar cambios

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- =========================================================
     JAVASCRIPT TABS
========================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    const tabs = document.querySelectorAll('.detail-tab');

    const panels = document.querySelectorAll('.tab-panel');


    tabs.forEach(function (tab) {

        tab.addEventListener('click', function () {

            const target = this.dataset.tab;


            tabs.forEach(function (item) {

                item.classList.remove('active');

            });


            panels.forEach(function (panel) {

                panel.classList.remove('active');

            });


            this.classList.add('active');


            const panel = document.getElementById(
                'tab-' + target
            );


            if (panel) {

                panel.classList.add('active');

            }

        });

    });

});

</script>


@endsection


{{-- =========================================================
     ABRIR MODAL DE OBSERVACIÓN SI HUBO ERROR
========================================================= --}}

@if($errors->has('observacion'))

<script>

document.addEventListener('DOMContentLoaded', function () {

    const modalElement = document.getElementById(
        'nuevaObservacionModal'
    );


    if (modalElement) {

        const modal = new bootstrap.Modal(modalElement);

        modal.show();

    }

});

</script>

@endif


{{-- =========================================================
     ABRIR MODAL DE SIGNOS VITALES SI HUBO ERROR
========================================================= --}}

@if(
    $errors->has('fecha_registro') ||
    $errors->has('presion_arterial') ||
    $errors->has('frecuencia_cardiaca') ||
    $errors->has('frecuencia_respiratoria') ||
    $errors->has('temperatura') ||
    $errors->has('spo2')
)

<script>

document.addEventListener('DOMContentLoaded', function () {

    const modalElement = document.getElementById(
        'nuevoSignoVitalModal'
    );


    if (modalElement) {

        const modal = new bootstrap.Modal(modalElement);

        modal.show();

    }

});

</script>

@endif
