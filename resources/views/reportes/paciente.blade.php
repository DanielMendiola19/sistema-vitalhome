@extends('layouts.app')

@section('title', 'Reportes del paciente')

@section('content')

<style>

    .reports-page {
        background: #f8fafc;
        min-height: calc(100vh - 70px);
        padding: 32px;
    }

    .reports-header {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 24px;
        margin-bottom: 28px;
    }

    .reports-title {
        color: #0f172a;
        font-size: 30px;
        font-weight: 700;
        margin: 0;
    }

    .reports-subtitle {
        color: #64748b;
        font-size: 14px;
        margin: 7px 0 0;
    }

    .reports-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        box-shadow: 0 2px 8px rgba(15,23,42,.04);
        overflow: hidden;
        margin-bottom: 22px;
    }

    .patient-header-card {
        padding: 22px 24px;
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

    .section-header {
        padding: 22px 24px;
        border-bottom: 1px solid #f1f5f9;
    }

    .section-title {
        color: #0f172a;
        font-size: 18px;
        font-weight: 700;
        margin: 0;
    }

    .section-subtitle {
        color: #64748b;
        font-size: 13px;
        margin-top: 4px;
    }

    .period-body {
        padding: 24px;
    }

    .period-options {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 24px;
    }

    .period-option {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #334155;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
    }

    .period-option input {
        accent-color: #10b981;
    }

    .range-container {
        margin-top: 22px;
    }

    .date-fields {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 250px));
        gap: 16px;
        margin-bottom: 20px;
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

    .btn-apply {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #10b981;
        border: 1px solid #10b981;
        color: white;
        padding: 10px 17px;
        border-radius: 9px;
        font-size: 14px;
        font-weight: 600;
        transition: .2s;
    }

    .btn-apply:hover {
        background: #059669;
        border-color: #059669;
        color: white;
    }

    .period-summary {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 7px 11px;
        border-radius: 999px;
        background: #f0fdf4;
        color: #15803d;
        font-size: 12px;
        font-weight: 700;
        margin-left: 10px;
    }

    .reports-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    .report-option {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 24px;
        box-shadow: 0 2px 8px rgba(15,23,42,.04);
        transition: .2s;
        display: flex;
        flex-direction: column;
        min-height: 220px;
    }

    .report-option:hover {
        border-color: #bbf7d0;
        box-shadow: 0 8px 20px rgba(15,23,42,.07);
        transform: translateY(-2px);
    }

    .report-icon {
        width: 46px;
        height: 46px;
        border-radius: 11px;
        background: #ecfdf5;
        color: #059669;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 21px;
        margin-bottom: 18px;
    }

    .report-title {
        color: #0f172a;
        font-size: 17px;
        font-weight: 700;
        margin-bottom: 7px;
    }

    .report-description {
        color: #64748b;
        font-size: 13px;
        line-height: 1.6;
        margin-bottom: 20px;
        flex-grow: 1;
    }

    .report-action {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #059669;
        font-size: 14px;
        font-weight: 700;
        text-decoration: none;
        border: none;
        padding: 0;
        background: transparent;
        align-self: flex-start;
    }

    .report-action:hover {
        color: #047857;
    }

    .general-report {
        grid-column: 1 / -1;
        border-color: #bbf7d0;
        background: linear-gradient(
            135deg,
            #ffffff 0%,
            #f0fdf4 100%
        );
    }

    .general-report .report-icon {
        background: #10b981;
        color: white;
    }

    .alert-custom {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(15,23,42,.04);
    }

    @media (max-width: 900px) {

        .patient-header-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .reports-grid {
            grid-template-columns: 1fr;
        }

        .general-report {
            grid-column: auto;
        }

    }

    @media (max-width: 768px) {

        .reports-page {
            padding: 20px 15px;
        }

        .reports-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .date-fields {
            grid-template-columns: 1fr;
        }

        .period-summary {
            margin-left: 0;
            margin-top: 10px;
        }

    }

    @media (max-width: 520px) {

        .patient-header-grid {
            grid-template-columns: 1fr;
        }

    }

</style>


<div class="reports-page">

    {{-- ====================================================== --}}
    {{-- HEADER --}}
    {{-- ====================================================== --}}

    <div class="reports-header">

        <div>

            <h1 class="reports-title">
                Reportes del paciente
            </h1>

            <p class="reports-subtitle">
                Consulta y generación de información clínica e histórica
            </p>

        </div>

        <a
            href="{{ route('reportes.index') }}"
            class="btn btn-light"
        >

            <i class="bi bi-arrow-left me-1"></i>

            Volver

        </a>

    </div>


    {{-- ====================================================== --}}
    {{-- ERRORES --}}
    {{-- ====================================================== --}}

    @if($errors->any())

        <div class="alert alert-danger alert-custom mb-3">

            <div class="fw-bold mb-2">

                <i class="bi bi-exclamation-triangle me-2"></i>

                No se pudo aplicar el período seleccionado.

            </div>

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- ====================================================== --}}
    {{-- INFORMACIÓN DEL PACIENTE --}}
    {{-- ====================================================== --}}

    <div class="reports-card">

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

                <div>

                    <div class="info-label">
                        Paciente
                    </div>

                    <div class="info-value">

                        {{ $paciente->nombre }}
                        {{ $paciente->apellido }}

                    </div>

                </div>


                <div>

                    <div class="info-label">
                        Carnet
                    </div>

                    <div class="info-value">

                        {{ $paciente->ci ?: '—' }}

                    </div>

                </div>


                <div>

                    <div class="info-label">
                        Edad
                    </div>

                    <div class="info-value">

                        {{ $edad !== null
                            ? $edad . ' años'
                            : '—'
                        }}

                    </div>

                </div>


                <div>

                    <div class="info-label">
                        Diagnóstico
                    </div>

                    <div class="info-value">

                        {{ $paciente->diagnostico ?: '—' }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ====================================================== --}}
    {{-- PERÍODO DEL REPORTE --}}
    {{-- ====================================================== --}}

    <div class="reports-card">

        <div class="section-header">

            <div class="section-title">
                Período del reporte
            </div>

            <div class="section-subtitle">
                Selecciona si deseas consultar todo el historial
                o solamente un rango de fechas.
            </div>

        </div>


        <div class="period-body">

            <form
                method="GET"
                action="{{ route(
                    'reportes.paciente',
                    ['paciente' => $paciente->id]
                ) }}"
                id="periodForm"
            >

                <div class="period-options">

                    {{-- TODO EL HISTORIAL --}}

                    <label class="period-option">

                        <input
                            type="radio"
                            name="periodo"
                            value="todo"
                            {{ ($periodo ?? 'todo') === 'todo'
                                ? 'checked'
                                : ''
                            }}
                        >

                        Todo el historial

                    </label>


                    {{-- RANGO DE FECHAS --}}

                    <label class="period-option">

                        <input
                            type="radio"
                            name="periodo"
                            value="rango"
                            {{ ($periodo ?? 'todo') === 'rango'
                                ? 'checked'
                                : ''
                            }}
                        >

                        Rango de fechas

                    </label>

                </div>


                {{-- ====================================================== --}}
                {{-- RANGO DE FECHAS --}}
                {{-- SOLO SE MUESTRA CUANDO SE SELECCIONA "RANGO" --}}
                {{-- ====================================================== --}}

                <div
                    class="range-container"
                    id="rangeContainer"
                    style="display: none;"
                >

                    <div class="date-fields">

                        {{-- DESDE --}}

                        <div>

                            <label class="form-label-custom">
                                Desde
                            </label>

                            <input
                                type="date"
                                name="desde"
                                value="{{ $desde ?? '' }}"
                                class="form-control form-control-custom"
                                id="desde"
                            >

                        </div>


                        {{-- HASTA --}}

                        <div>

                            <label class="form-label-custom">
                                Hasta
                            </label>

                            <input
                                type="date"
                                name="hasta"
                                value="{{ $hasta ?? '' }}"
                                class="form-control form-control-custom"
                                id="hasta"
                            >

                        </div>

                    </div>


                    {{-- BOTÓN APLICAR --}}

                    <button
                        type="submit"
                        class="btn-apply"
                    >

                        <i class="bi bi-funnel"></i>

                        Aplicar período

                    </button>


                    {{-- RESUMEN DEL RANGO APLICADO --}}

                    @if(
                        ($periodo ?? 'todo') === 'rango'
                        && $desde
                        && $hasta
                    )

                        <span class="period-summary">

                            <i class="bi bi-calendar-range"></i>

                            {{ \Carbon\Carbon::parse($desde)->format('d/m/Y') }}

                            -

                            {{ \Carbon\Carbon::parse($hasta)->format('d/m/Y') }}

                        </span>

                    @endif

                </div>

            </form>

        </div>

    </div>


    {{-- ====================================================== --}}
    {{-- TIPOS DE REPORTE --}}
    {{-- ====================================================== --}}

    <div class="reports-grid">


        {{-- INFORMACIÓN GENERAL --}}

        <div class="report-option">

            <div class="report-icon">

                <i class="bi bi-person-vcard"></i>

            </div>

            <div class="report-title">
                Información general
            </div>

            <div class="report-description">

                Datos personales del paciente, carnet,
                fecha de nacimiento, edad, sexo, teléfono,
                dirección, diagnóstico y observaciones generales.

            </div>

            <a
    href="{{ route(
        'reportes.informacion_general',
        [
            'paciente' => $paciente->id,
            'periodo' => $periodo ?? 'todo',
            'desde' => $desde ?? null,
            'hasta' => $hasta ?? null,
        ]
    ) }}"
    class="report-action"
>

    Ver reporte

    <i class="bi bi-arrow-right"></i>

</a>

        </div>


        {{-- KARDEX --}}

        <div class="report-option">

            <div class="report-icon">

                <i class="bi bi-clipboard2-pulse"></i>

            </div>

            <div class="report-title">
                Kardex / Tratamientos
            </div>

            <div class="report-description">

                Historial de medicamentos, dosis,
                frecuencia, vías de administración,
                horarios, indicaciones y estados
                de los tratamientos.

            </div>

            <a
    href="{{ route(
        'reportes.kardex',
        [
            'paciente' => $paciente->id,
            'periodo' => $periodo ?? 'todo',
            'desde' => $desde ?? null,
            'hasta' => $hasta ?? null,
        ]
    ) }}"
    class="report-action"
>

    Ver reporte

    <i class="bi bi-arrow-right"></i>

</a>

        </div>


        {{-- HISTORIA CLÍNICA --}}

        <div class="report-option">

            <div class="report-icon">

                <i class="bi bi-journal-medical"></i>

            </div>

            <div class="report-title">
                Historia clínica
            </div>

            <div class="report-description">

                Consulta del historial de observaciones
                clínicas registradas durante la permanencia
                del paciente en Vital Homme.

            </div>

            <a
    href="{{ route(
        'reportes.historia_clinica',
        [
            'paciente' => $paciente->id,
            'periodo' => $periodo ?? 'todo',
            'desde' => $desde ?? null,
            'hasta' => $hasta ?? null,
        ]
    ) }}"
    class="report-action"
>

    Ver reporte

    <i class="bi bi-arrow-right"></i>

</a>

        </div>


        {{-- SIGNOS VITALES --}}

        <div class="report-option">

            <div class="report-icon">

                <i class="bi bi-heart-pulse"></i>

            </div>

            <div class="report-title">
                Signos vitales
            </div>

            <div class="report-description">

                Historial de controles y registros
                de signos vitales del paciente dentro
                del período seleccionado.

            </div>

            <a
    href="{{ route(
        'reportes.signos_vitales',
        [
            'paciente' => $paciente->id,
            'periodo' => $periodo ?? 'todo',
            'desde' => $desde ?? null,
            'hasta' => $hasta ?? null,
        ]
    ) }}"
    class="report-action"
>

    Ver reporte

    <i class="bi bi-arrow-right"></i>

</a>

        </div>


        {{-- REPORTE GENERAL --}}

        <div class="report-option general-report">

            <div class="report-icon">

                <i class="bi bi-file-earmark-medical"></i>

            </div>

            <div class="report-title">
                Reporte general del paciente
            </div>

            <div class="report-description">

                Consolida en un único reporte la información
                general del paciente, Kardex y tratamientos,
                historia clínica y registros de signos vitales.

                El contenido histórico respetará el período
                seleccionado anteriormente.

            </div>

            <a
    href="{{ route(
        'reportes.general',
        [
            'paciente' => $paciente->id,
            'periodo' => $periodo ?? 'todo',
            'desde' => $desde ?? null,
            'hasta' => $hasta ?? null,
        ]
    ) }}"
    class="report-action"
>

    Ver reporte

    <i class="bi bi-arrow-right"></i>

</a>

        </div>

    </div>

</div>


{{-- ====================================================== --}}
{{-- SCRIPT PERÍODO --}}
{{-- ====================================================== --}}

<script>

    document.addEventListener('DOMContentLoaded', function () {

        const periodInputs = document.querySelectorAll(
            'input[name="periodo"]'
        );

        const rangeContainer =
            document.getElementById('rangeContainer');

        const desde =
            document.getElementById('desde');

        const hasta =
            document.getElementById('hasta');


        function actualizarPeriodo() {

            const seleccionado = document.querySelector(
                'input[name="periodo"]:checked'
            );

            const esRango =
                seleccionado &&
                seleccionado.value === 'rango';


            /*
             * Mostrar los campos solamente
             * si está seleccionado "Rango de fechas".
             */
            rangeContainer.style.display =
                esRango ? 'block' : 'none';


            /*
             * Las fechas solamente serán enviadas
             * y obligatorias cuando se utilice rango.
             */
            desde.disabled = !esRango;
            hasta.disabled = !esRango;

            desde.required = esRango;
            hasta.required = esRango;

        }


        periodInputs.forEach(function (input) {

            input.addEventListener(
                'change',
                actualizarPeriodo
            );

        });


        /*
         * Estado inicial:
         *
         * Todo el historial -> bloque oculto
         * Rango de fechas    -> bloque visible
         */
        actualizarPeriodo();

    });

</script>

@endsection
