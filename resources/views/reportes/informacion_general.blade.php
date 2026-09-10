@extends('layouts.app')

@section('title', 'Reporte - Información general')

@section('content')

@php

    $edad = null;

    if ($paciente->fecha_nacimiento) {
        $edad = $paciente->fecha_nacimiento->age;
    }

    $codigoReporte =
        'VH-IG-' .
        str_pad(
            $paciente->id,
            5,
            '0',
            STR_PAD_LEFT
        ) .
        '-' .
        now()->format('Ymd');

@endphp


@if($modoPdf ?? false)


{{-- ============================================================
     MODO PDF - DOMPDF
     ============================================================ --}}

<style>

    @page {
        size: letter portrait;
        margin: 18px 24px;
    }

    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        padding: 0;

        font-family:
            DejaVu Sans,
            Arial,
            sans-serif;

        color: #0f172a;

        font-size: 9px;
    }


    /* ======================================================
       DOCUMENTO
       ====================================================== */

    .pdf-document {
        width: 100%;
    }


    /* ======================================================
       MEMBRETE
       ====================================================== */

    .pdf-header-table {
        width: 100%;

        border-collapse: collapse;

        table-layout: fixed;
    }

    .pdf-header-table td {
        vertical-align: middle;
    }

    .pdf-logo-cell {
        width: 25%;

        padding-right: 10px;
    }

    .pdf-logo {
        width: 125px;

        height: auto;
    }

    .pdf-title-cell {
        width: 50%;

        padding:
            0
            14px;

        text-align: center;

        border-left:
            1px solid #cbd5e1;

        border-right:
            1px solid #cbd5e1;
    }

    .pdf-overline {
        margin-bottom: 5px;

        color: #64748b;

        font-size: 6px;

        font-weight: bold;

        letter-spacing: 1px;

        text-transform: uppercase;
    }

    .pdf-title {
        margin: 0;

        color: #0f172a;

        font-size: 15px;

        font-weight: bold;

        line-height: 1.15;

        text-transform: uppercase;
    }

    .pdf-subtitle {
        margin-top: 5px;

        color: #64748b;

        font-size: 6.5px;

        line-height: 1.3;
    }

    .pdf-meta-cell {
        width: 25%;

        padding-left: 12px;
    }

    .pdf-meta-block {
        padding: 4px 0;

        border-bottom:
            1px solid #e2e8f0;
    }

    .pdf-meta-block:last-child {
        border-bottom: none;
    }

    .pdf-meta-label {
        color: #94a3b8;

        font-size: 5.5px;

        font-weight: bold;

        text-transform: uppercase;
    }

    .pdf-meta-value {
        margin-top: 2px;

        color: #0f172a;

        font-size: 7px;

        font-weight: bold;
    }


    /* ======================================================
       LÍNEA CORPORATIVA
       ====================================================== */

    .pdf-brand-line {
        width: 100%;

        height: 4px;

        margin-top: 12px;

        border-collapse: collapse;
    }

    .pdf-brand-line td {
        height: 4px;

        padding: 0;
    }

    .pdf-brand-dark {
        width: 17%;

        background: #047857;
    }

    .pdf-brand-lime {
        width: 11%;

        background: #84cc16;
    }

    .pdf-brand-green {
        background: #10b981;
    }


    /* ======================================================
       PACIENTE
       ====================================================== */

    .pdf-patient-box {
        margin-top: 13px;

        padding:
            10px
            12px;

        background: #f0fdf4;

        border-left:
            4px solid #10b981;
    }

    .pdf-patient-table {
        width: 100%;

        border-collapse: collapse;

        table-layout: fixed;
    }

    .pdf-patient-table td {
        vertical-align: middle;
    }

    .pdf-patient-main {
        width: 42%;
    }

    .pdf-patient-field {
        width: 19%;

        padding-left: 12px;

        border-left:
            1px solid #dbe4ea;
    }

    .pdf-small-label {
        display: block;

        color: #94a3b8;

        font-size: 5.5px;

        font-weight: bold;

        text-transform: uppercase;
    }

    .pdf-patient-name {
        margin-top: 3px;

        color: #0f172a;

        font-size: 14px;

        font-weight: bold;
    }

    .pdf-patient-ci {
        margin-top: 3px;

        color: #475569;

        font-size: 7px;
    }

    .pdf-small-value {
        display: block;

        margin-top: 3px;

        color: #0f172a;

        font-size: 7.5px;

        font-weight: bold;
    }


    /* ======================================================
       SECCIONES
       ====================================================== */

    .pdf-section {
        margin-top: 13px;

        page-break-inside: avoid;
    }

    .pdf-section-header {
        width: 100%;

        border-collapse: collapse;
    }

    .pdf-section-number {
        width: 28px;

        padding: 6px 0;

        background: #047857;

        color: #ffffff;

        text-align: center;

        font-size: 9px;

        font-weight: bold;
    }

    .pdf-section-title {
        padding:
            6px
            10px;

        background: #ecfdf5;

        color: #1e293b;

        font-size: 8px;

        font-weight: bold;

        text-transform: uppercase;
    }


    /* ======================================================
       DATOS PERSONALES
       ====================================================== */

    .pdf-personal-table {
        width: 96%;

        margin:
            6px
            auto
            0;

        border-collapse: collapse;

        table-layout: fixed;
    }

    .pdf-personal-table td {
        width: 50%;

        padding:
            6px
            10px;

        border-bottom:
            1px solid #e5e7eb;

        vertical-align: middle;
    }

    .pdf-data-table {
        width: 100%;

        border-collapse: collapse;
    }

    .pdf-data-label-cell {
        width: 43%;

        color: #475569;

        font-size: 6.5px;

        font-weight: bold;
    }

    .pdf-data-value-cell {
        width: 57%;

        color: #0f172a;

        font-size: 7px;

        font-weight: bold;
    }


    /* ======================================================
       DIAGNÓSTICO / OBSERVACIONES
       ====================================================== */

    .pdf-content-box {
        width: 96%;

        min-height: 40px;

        margin:
            7px
            auto
            0;

        padding:
            10px
            12px;

        border:
            1px solid #dbe3e8;

        background:
            #fcfdfe;

        color:
            #334155;

        font-size:
            7px;

        line-height:
            1.5;
    }


    /* ======================================================
       CONSTANCIA
       ====================================================== */

    .pdf-notice {
        width: 96%;

        margin:
            14px
            auto
            0;

        padding:
            9px
            11px;

        background:
            #f0fdfa;

        border-left:
            3px solid #0f766e;

        color:
            #475569;

        font-size:
            6px;

        line-height:
            1.5;

        page-break-inside: avoid;
    }


    /* ======================================================
       FIRMAS
       ====================================================== */

    .pdf-signature-table {
        width: 82%;

        margin:
            38px
            auto
            0;

        border-collapse: collapse;

        table-layout: fixed;

        page-break-inside: avoid;
    }

    .pdf-signature-table td {
        width: 50%;

        padding:
            0
            22px;

        text-align: center;
    }

    .pdf-signature-box {
        padding-top: 5px;

        border-top:
            1px solid #475569;
    }

    .pdf-signature-name {
        color:
            #0f172a;

        font-size:
            6.8px;

        font-weight:
            bold;
    }

    .pdf-signature-caption {
        margin-top:
            2px;

        color:
            #94a3b8;

        font-size:
            5.5px;
    }


    /* ======================================================
       PIE
       ====================================================== */

    .pdf-footer-table {
        width: 100%;

        margin-top: 22px;

        padding-top: 7px;

        border-top:
            1px solid #cbd5e1;

        border-collapse: collapse;

        page-break-inside: avoid;
    }

    .pdf-footer-table td {
        vertical-align: bottom;
    }

    .pdf-footer-left {
        width: 70%;

        color:
            #64748b;

        font-size:
            5.5px;

        line-height:
            1.4;
    }

    .pdf-footer-left strong {
        color:
            #1e293b;

        font-size:
            6.5px;
    }

    .pdf-footer-right {
        width: 30%;

        color:
            #64748b;

        font-size:
            5.5px;

        line-height:
            1.4;

        text-align:
            right;
    }

    .pdf-footer-code {
        color:
            #334155;

        font-weight:
            bold;
    }

    .pdf-footer-green-line {
        width:
            100%;

        height:
            5px;

        margin-top:
            10px;

        background:
            #059669;
    }

</style>


<div class="pdf-document">


    {{-- ====================================================== --}}
    {{-- MEMBRETE --}}
    {{-- ====================================================== --}}

    <table class="pdf-header-table">

        <tr>


            <td class="pdf-logo-cell">

                @if(!empty($logoBase64))

                    <img
                        src="{{ $logoBase64 }}"
                        alt="Vital Homme"
                        class="pdf-logo"
                    >

                @endif

            </td>


            <td class="pdf-title-cell">

                <div class="pdf-overline">
                    Informe institucional
                </div>


                <div class="pdf-title">
                    Información general
                    del paciente
                </div>


                <div class="pdf-subtitle">

                    Registro de identificación,
                    información personal
                    y antecedentes generales

                </div>

            </td>


            <td class="pdf-meta-cell">


                <div class="pdf-meta-block">

                    <div class="pdf-meta-label">
                        Código del documento
                    </div>

                    <div class="pdf-meta-value">
                        {{ $codigoReporte }}
                    </div>

                </div>


                <div class="pdf-meta-block">

                    <div class="pdf-meta-label">
                        Fecha de emisión
                    </div>

                    <div class="pdf-meta-value">
                        {{ now()->format('d/m/Y') }}
                    </div>

                </div>


                <div class="pdf-meta-block">

                    <div class="pdf-meta-label">
                        Hora
                    </div>

                    <div class="pdf-meta-value">
                        {{ now()->format('H:i') }}
                    </div>

                </div>


            </td>


        </tr>

    </table>


    {{-- LÍNEA CORPORATIVA --}}

    <table class="pdf-brand-line">

        <tr>

            <td class="pdf-brand-dark"></td>

            <td class="pdf-brand-lime"></td>

            <td class="pdf-brand-green"></td>

        </tr>

    </table>


    {{-- ====================================================== --}}
    {{-- PACIENTE --}}
    {{-- ====================================================== --}}

    <div class="pdf-patient-box">


        <table class="pdf-patient-table">

            <tr>


                <td class="pdf-patient-main">

                    <span class="pdf-small-label">
                        Paciente
                    </span>


                    <div class="pdf-patient-name">

                        {{ $paciente->nombre }}
                        {{ $paciente->apellido }}

                    </div>


                    <div class="pdf-patient-ci">

                        CI:
                        {{ $paciente->ci ?: 'No registrado' }}

                    </div>

                </td>


                <td class="pdf-patient-field">

                    <span class="pdf-small-label">
                        Edad
                    </span>

                    <span class="pdf-small-value">

                        {{ $edad !== null
                            ? $edad . ' años'
                            : '—'
                        }}

                    </span>

                </td>


                <td class="pdf-patient-field">

                    <span class="pdf-small-label">
                        Fecha de nacimiento
                    </span>

                    <span class="pdf-small-value">

                        {{ $paciente->fecha_nacimiento
                            ? $paciente->fecha_nacimiento->format('d/m/Y')
                            : '—'
                        }}

                    </span>

                </td>


                <td class="pdf-patient-field">

                    <span class="pdf-small-label">
                        Sexo
                    </span>

                    <span class="pdf-small-value">

                        {{ $paciente->sexo ?: '—' }}

                    </span>

                </td>


            </tr>

        </table>


    </div>


    {{-- ====================================================== --}}
    {{-- 1. DATOS PERSONALES --}}
    {{-- ====================================================== --}}

    <div class="pdf-section">


        <table class="pdf-section-header">

            <tr>

                <td class="pdf-section-number">
                    1
                </td>

                <td class="pdf-section-title">
                    Datos personales
                </td>

            </tr>

        </table>


        <table class="pdf-personal-table">


            <tr>


                <td>

                    <table class="pdf-data-table">

                        <tr>

                            <td class="pdf-data-label-cell">
                                Nombre
                            </td>

                            <td class="pdf-data-value-cell">
                                {{ $paciente->nombre ?: 'No registrado' }}
                            </td>

                        </tr>

                    </table>

                </td>


                <td>

                    <table class="pdf-data-table">

                        <tr>

                            <td class="pdf-data-label-cell">
                                Fecha de nacimiento
                            </td>

                            <td class="pdf-data-value-cell">

                                {{ $paciente->fecha_nacimiento
                                    ? $paciente->fecha_nacimiento->format('d/m/Y')
                                    : 'No registrada'
                                }}

                            </td>

                        </tr>

                    </table>

                </td>


            </tr>


            <tr>


                <td>

                    <table class="pdf-data-table">

                        <tr>

                            <td class="pdf-data-label-cell">
                                Apellido
                            </td>

                            <td class="pdf-data-value-cell">
                                {{ $paciente->apellido ?: 'No registrado' }}
                            </td>

                        </tr>

                    </table>

                </td>


                <td>

                    <table class="pdf-data-table">

                        <tr>

                            <td class="pdf-data-label-cell">
                                Edad
                            </td>

                            <td class="pdf-data-value-cell">

                                {{ $edad !== null
                                    ? $edad . ' años'
                                    : 'No registrada'
                                }}

                            </td>

                        </tr>

                    </table>

                </td>


            </tr>


            <tr>


                <td>

                    <table class="pdf-data-table">

                        <tr>

                            <td class="pdf-data-label-cell">
                                Carnet de identidad
                            </td>

                            <td class="pdf-data-value-cell">
                                {{ $paciente->ci ?: 'No registrado' }}
                            </td>

                        </tr>

                    </table>

                </td>


                <td>

                    <table class="pdf-data-table">

                        <tr>

                            <td class="pdf-data-label-cell">
                                Teléfono
                            </td>

                            <td class="pdf-data-value-cell">
                                {{ $paciente->telefono ?: 'No registrado' }}
                            </td>

                        </tr>

                    </table>

                </td>


            </tr>


            <tr>


                <td>

                    <table class="pdf-data-table">

                        <tr>

                            <td class="pdf-data-label-cell">
                                Sexo
                            </td>

                            <td class="pdf-data-value-cell">
                                {{ $paciente->sexo ?: 'No registrado' }}
                            </td>

                        </tr>

                    </table>

                </td>


                <td>

                    <table class="pdf-data-table">

                        <tr>

                            <td class="pdf-data-label-cell">
                                Dirección
                            </td>

                            <td class="pdf-data-value-cell">
                                {{ $paciente->direccion ?: 'No registrada' }}
                            </td>

                        </tr>

                    </table>

                </td>


            </tr>


        </table>


    </div>


    {{-- ====================================================== --}}
    {{-- 2. DIAGNÓSTICO --}}
    {{-- ====================================================== --}}

    <div class="pdf-section">


        <table class="pdf-section-header">

            <tr>

                <td class="pdf-section-number">
                    2
                </td>

                <td class="pdf-section-title">
                    Diagnóstico registrado
                </td>

            </tr>

        </table>


        <div class="pdf-content-box">

            {{ $paciente->diagnostico
                ?: 'No se ha registrado un diagnóstico.'
            }}

        </div>


    </div>


    {{-- ====================================================== --}}
    {{-- 3. OBSERVACIONES --}}
    {{-- ====================================================== --}}

    <div class="pdf-section">


        <table class="pdf-section-header">

            <tr>

                <td class="pdf-section-number">
                    3
                </td>

                <td class="pdf-section-title">
                    Observaciones generales
                </td>

            </tr>

        </table>


        <div class="pdf-content-box">

            {{ $paciente->observaciones
                ?: 'No se han registrado observaciones generales.'
            }}

        </div>


    </div>


    {{-- ====================================================== --}}
    {{-- CONSTANCIA --}}
    {{-- ====================================================== --}}

    <div class="pdf-notice">

        El presente documento contiene la información
        general registrada actualmente en el sistema
        institucional de Vital Homme para el paciente
        identificado en el encabezado.

        Su contenido refleja los datos disponibles
        al momento de su emisión.

    </div>


    {{-- ====================================================== --}}
    {{-- FIRMAS --}}
    {{-- ====================================================== --}}

    <table class="pdf-signature-table">

        <tr>


            <td>

                <div class="pdf-signature-box">

                    <div class="pdf-signature-name">
                        Responsable autorizado
                    </div>

                    <div class="pdf-signature-caption">
                        Nombre, firma y sello
                    </div>

                </div>

            </td>


            <td>

                <div class="pdf-signature-box">

                    <div class="pdf-signature-name">
                        Dirección / Administración
                    </div>

                    <div class="pdf-signature-caption">
                        Firma y sello institucional
                    </div>

                </div>

            </td>


        </tr>

    </table>


    {{-- ====================================================== --}}
    {{-- FOOTER --}}
    {{-- ====================================================== --}}

    <table class="pdf-footer-table">

        <tr>


            <td class="pdf-footer-left">

                <strong>
                    Vital Homme
                </strong>

                <br>

                Centro de atención y cuidado
                de adultos mayores

                <br>

                Documento generado mediante
                el sistema institucional VitalHome.

            </td>


            <td class="pdf-footer-right">

                <span class="pdf-footer-code">
                    {{ $codigoReporte }}
                </span>

                <br>

                {{ now()->format('d/m/Y H:i') }}

                <br>

                Página 1 de 1

            </td>


        </tr>

    </table>


    <div class="pdf-footer-green-line"></div>


</div>


@else


{{-- ============================================================
     MODO NORMAL DEL SISTEMA
     ============================================================ --}}

<style>

    :root {
        --vh-green-dark: #047857;
        --vh-green: #059669;
        --vh-green-light: #10b981;
        --vh-lime: #84cc16;

        --vh-text: #0f172a;
        --vh-text-soft: #475569;
        --vh-muted: #94a3b8;

        --vh-line: #dbe3e8;
        --vh-soft: #f8fafc;
        --vh-green-soft: #ecfdf5;
    }


    /* ==========================================================
       PÁGINA DEL SISTEMA
       ========================================================== */

    .report-page {
        min-height: calc(100vh - 70px);
        padding: 28px 20px 45px;
        background: #f1f5f9;
    }


    /* ==========================================================
       TOOLBAR
       ========================================================== */

    .report-toolbar {
        width: 198mm;
        max-width: 100%;
        margin: 0 auto 16px;

        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
    }

    .toolbar-title {
        margin: 0;

        color: var(--vh-text);

        font-size: 24px;

        font-weight: 700;
    }

    .toolbar-subtitle {
        margin: 3px 0 0;

        color: #64748b;

        font-size: 12px;
    }

    .toolbar-actions {
        display: flex;

        align-items: center;

        gap: 8px;

        flex-wrap: wrap;
    }

    .btn-report-action {
        min-height: 38px;

        padding:
            9px
            15px;

        border: none;

        border-radius: 8px;

        display: inline-flex;

        align-items: center;

        justify-content: center;

        gap: 7px;

        font-size: 13px;

        font-weight: 600;

        text-decoration: none;

        cursor: pointer;

        transition: .2s ease;
    }

    .btn-print-report {
        background: var(--vh-green);

        color: #ffffff;
    }

    .btn-print-report:hover {
        background: var(--vh-green-dark);

        color: #ffffff;
    }

    .btn-pdf-report {
        background: #0f172a;

        color: #ffffff;
    }

    .btn-pdf-report:hover {
        background: #1e293b;

        color: #ffffff;
    }


    /* ==========================================================
       DOCUMENTO CARTA
       ========================================================== */

    .official-document {
        width: 198mm;

        max-width: 100%;

        min-height: 263mm;

        margin: 0 auto;

        box-sizing: border-box;

        background: #ffffff;

        border: 1px solid #d7dee5;

        border-radius: 3px;

        box-shadow:
            0 8px 25px
            rgba(15, 23, 42, .08);

        overflow: hidden;

        font-family:
            Arial,
            Helvetica,
            sans-serif;

        color: var(--vh-text);
    }


    /* ==========================================================
       MEMBRETE
       ========================================================== */

    .document-header {
        padding:
            6mm
            7mm
            3.5mm;

        box-sizing:
            border-box;
    }

    .header-main {
        width: 100%;

        display: grid;

        grid-template-columns:
            45mm
            minmax(0, 1fr)
            35mm;

        align-items: center;

        gap: 4mm;
    }


    /* ==========================================================
       LOGO
       ========================================================== */

    .logo-area {
        width: 45mm;

        height: 20mm;

        display: flex;

        align-items: center;

        justify-content: center;

        overflow: visible;
    }

    .institution-logo {
        display: block;

        width: 43mm;

        max-width: 43mm;

        height: auto;

        max-height: 19mm;

        object-fit: contain;

        object-position: center;
    }


    /* ==========================================================
       TÍTULO
       ========================================================== */

    .header-title-area {
        min-width: 0;

        padding:
            0
            4mm;

        text-align: center;

        border-left:
            1px solid #cbd5e1;

        border-right:
            1px solid #cbd5e1;
    }

    .document-overline {
        margin-bottom: 1.1mm;

        color: #64748b;

        font-size: 6pt;

        font-weight: 700;

        text-transform: uppercase;

        letter-spacing: 1.2px;
    }

    .document-title {
        margin: 0;

        color: var(--vh-text);

        font-size: 12pt;

        font-weight: 800;

        line-height: 1.12;

        text-transform: uppercase;
    }

    .document-description {
        margin-top: 1.2mm;

        color: #64748b;

        font-size: 6pt;

        line-height: 1.25;
    }


    /* ==========================================================
       METADATOS
       ========================================================== */

    .header-meta {
        min-width: 0;
    }

    .meta-item {
        padding:
            1.1mm
            0;

        border-bottom:
            1px solid #e2e8f0;
    }

    .meta-item:last-child {
        border-bottom: none;
    }

    .meta-label {
        display: block;

        margin-bottom: .5mm;

        color: var(--vh-muted);

        font-size: 5pt;

        font-weight: 700;

        text-transform: uppercase;

        letter-spacing: .25px;
    }

    .meta-value {
        display: block;

        color: var(--vh-text);

        font-size: 6.2pt;

        font-weight: 700;

        line-height: 1.15;

        overflow-wrap: anywhere;
    }


    /* ==========================================================
       LÍNEA CORPORATIVA
       ========================================================== */

    .header-accent {
        width: 100%;

        height: 1mm;

        margin-top: 3.5mm;

        display: grid;

        grid-template-columns:
            20mm
            13mm
            1fr;

        overflow: hidden;

        border-radius: 20px;
    }

    .accent-dark {
        background: var(--vh-green-dark);
    }

    .accent-light {
        background: var(--vh-lime);
    }

    .accent-main {
        background: var(--vh-green-light);
    }


    /* ==========================================================
       CONTENIDO
       ========================================================== */

    .document-content {
        padding:
            0
            7mm
            4mm;

        box-sizing:
            border-box;
    }


    /* ==========================================================
       IDENTIFICACIÓN
       ========================================================== */

    .patient-hero {
        display: grid;

        grid-template-columns:
            2fr
            .65fr
            1fr
            .75fr;

        align-items: center;

        margin-bottom: 3.5mm;

        padding:
            2.7mm
            4mm;

        border-left:
            1.2mm solid
            var(--vh-green-light);

        border-radius:
            1.5mm;

        background:
            linear-gradient(
                90deg,
                #f0fdf4 0%,
                #f8fafc 60%,
                #ffffff 100%
            );
    }

    .patient-main {
        min-width: 0;

        padding-right: 3mm;
    }

    .patient-name {
        color: var(--vh-text);

        font-size: 11pt;

        font-weight: 800;

        line-height: 1.15;
    }

    .patient-ci {
        margin-top: .8mm;

        color: var(--vh-text-soft);

        font-size: 6pt;
    }

    .hero-field {
        min-height: 9mm;

        padding:
            .8mm
            3mm;

        border-left:
            1px solid #dbe4ea;

        display: flex;

        flex-direction: column;

        justify-content: center;
    }

    .small-label {
        color: var(--vh-muted);

        font-size: 5pt;

        font-weight: 700;

        text-transform: uppercase;

        letter-spacing: .2px;
    }

    .small-value {
        margin-top: .8mm;

        color: var(--vh-text);

        font-size: 6.5pt;

        font-weight: 700;

        line-height: 1.2;
    }


    /* ==========================================================
       SECCIONES
       ========================================================== */

    .document-section {
        margin-bottom: 3.2mm;
    }

    .document-section:last-child {
        margin-bottom: 0;
    }

    .section-header {
        display: flex;

        align-items: center;

        gap: 2mm;

        margin-bottom: 1.5mm;
    }

    .section-icon {
        width: 6mm;

        height: 6mm;

        flex:
            0
            0
            6mm;

        border-radius:
            1.4mm;

        display: flex;

        align-items: center;

        justify-content: center;

        background:
            var(--vh-green-dark);

        color:
            #ffffff;

        font-size:
            7pt;
    }

    .section-title-wrap {
        flex: 1;

        padding:
            1.25mm
            3mm;

        border-radius:
            1.2mm;

        background:
            linear-gradient(
                90deg,
                #ecfdf5 0%,
                #f8fafc 75%,
                #ffffff 100%
            );
    }

    .section-title {
        margin: 0;

        color:
            #1e293b;

        font-size:
            7pt;

        font-weight:
            800;

        text-transform:
            uppercase;

        letter-spacing:
            .25px;
    }


    /* ==========================================================
       DATOS PERSONALES
       ========================================================== */

    .personal-data {
        display: grid;

        grid-template-columns:
            1fr
            1fr;

        column-gap: 8mm;

        padding:
            0
            3mm;
    }

    .data-column {
        min-width: 0;
    }

    .data-row {
        display: grid;

        grid-template-columns:
            34mm
            minmax(0, 1fr);

        align-items: baseline;

        gap: 2mm;

        min-height: 5.7mm;

        padding:
            .9mm
            .5mm;

        border-bottom:
            1px solid #e5e7eb;
    }

    .data-row:last-child {
        border-bottom: none;
    }

    .data-label {
        color:
            var(--vh-text-soft);

        font-size:
            5.5pt;

        font-weight:
            700;

        line-height:
            1.25;
    }

    .data-value {
        color:
            var(--vh-text);

        font-size:
            6.3pt;

        font-weight:
            600;

        line-height:
            1.3;

        word-break:
            break-word;
    }


    /* ==========================================================
       DIAGNÓSTICO / OBSERVACIONES
       ========================================================== */

    .content-box {
        margin:
            0
            3mm;

        min-height:
            10mm;

        padding:
            2.2mm
            3mm;

        box-sizing:
            border-box;

        border:
            1px solid var(--vh-line);

        border-radius:
            1.5mm;

        background:
            #fcfdfe;

        color:
            #334155;

        font-size:
            6.4pt;

        line-height:
            1.4;

        white-space:
            pre-line;
    }


    /* ==========================================================
       CONSTANCIA
       ========================================================== */

    .document-notice {
        display:
            flex;

        align-items:
            flex-start;

        gap:
            2.5mm;

        margin:
            3.5mm
            3mm
            0;

        padding:
            2.2mm
            3mm;

        border-radius:
            1.5mm;

        background:
            #f0fdfa;

        color:
            #475569;

        font-size:
            5.5pt;

        line-height:
            1.4;
    }

    .notice-icon {
        width:
            5mm;

        height:
            5mm;

        flex:
            0
            0
            5mm;

        border-radius:
            50%;

        display:
            flex;

        align-items:
            center;

        justify-content:
            center;

        background:
            #0f766e;

        color:
            #ffffff;

        font-size:
            6pt;
    }


    /* ==========================================================
       FIRMAS
       ========================================================== */

    .signatures {
        display:
            grid;

        grid-template-columns:
            1fr
            1fr;

        gap:
            22mm;

        margin:
            10mm
            14mm
            0;
    }

    .signature {
        text-align:
            center;
    }

    .signature-line {
        border-top:
            1px solid #475569;

        margin-bottom:
            1.4mm;
    }

    .signature-name {
        color:
            var(--vh-text);

        font-size:
            6.2pt;

        font-weight:
            700;
    }

    .signature-caption {
        margin-top:
            .6mm;

        color:
            var(--vh-muted);

        font-size:
            5.2pt;
    }


    /* ==========================================================
       FOOTER
       ========================================================== */

    .document-footer {
        display:
            grid;

        grid-template-columns:
            1fr
            auto;

        align-items:
            end;

        gap:
            5mm;

        margin-top:
            4.5mm;

        padding-top:
            2mm;

        border-top:
            1px solid #cbd5e1;
    }

    .footer-left {
        color:
            #64748b;

        font-size:
            5.1pt;

        line-height:
            1.4;
    }

    .footer-left strong {
        display:
            block;

        margin-bottom:
            .4mm;

        color:
            #1e293b;

        font-size:
            6pt;
    }

    .footer-right {
        color:
            #64748b;

        font-size:
            5.1pt;

        line-height:
            1.4;

        text-align:
            right;
    }

    .footer-code {
        color:
            #334155;

        font-weight:
            700;
    }

    .footer-accent {
        height:
            1.5mm;

        margin:
            3mm
            -7mm
            -4mm;

        background:
            linear-gradient(
                90deg,
                #047857 0%,
                #10b981 72%,
                #84cc16 100%
            );
    }


    /* ==========================================================
       RESPONSIVE
       ========================================================== */

    @media screen and (max-width: 850px) {

        .report-page {
            padding:
                18px
                12px;
        }

        .report-toolbar {
            width:
                100%;

            align-items:
                flex-start;

            flex-direction:
                column;
        }

        .official-document {
            width:
                100%;

            min-height:
                auto;
        }

        .document-header {
            padding:
                20px
                18px
                14px;
        }

        .header-main {
            grid-template-columns:
                1fr;
        }

        .logo-area {
            width:
                100%;

            justify-content:
                center;
        }

        .header-title-area {
            padding:
                14px
                0;

            border-left:
                none;

            border-right:
                none;

            border-top:
                1px solid #e2e8f0;

            border-bottom:
                1px solid #e2e8f0;
        }

        .header-meta {
            display:
                grid;

            grid-template-columns:
                repeat(3, 1fr);

            gap:
                12px;
        }

        .document-content {
            padding:
                0
                18px
                18px;
        }

        .patient-hero {
            grid-template-columns:
                1fr
                1fr;
        }

        .patient-main {
            grid-column:
                1 / -1;

            margin-bottom:
                8px;
        }

        .hero-field {
            border-left:
                none;
        }

        .personal-data {
            grid-template-columns:
                1fr;

            padding:
                0;
        }

        .data-row {
            grid-template-columns:
                145px
                1fr;
        }

        .content-box {
            margin:
                0;
        }

        .document-notice {
            margin-left:
                0;

            margin-right:
                0;
        }

        .signatures {
            gap:
                30px;

            margin-left:
                20px;

            margin-right:
                20px;
        }

        .footer-accent {
            margin-left:
                -18px;

            margin-right:
                -18px;

            margin-bottom:
                -18px;
        }

    }


    /* ==========================================================
       IMPRESIÓN
       ========================================================== */

    @media print {

        @page {
            size: letter portrait;

            margin:
                8mm
                9mm;
        }

        html,
        body {
            margin:
                0 !important;

            padding:
                0 !important;

            background:
                #ffffff !important;
        }

        body {
            -webkit-print-color-adjust:
                exact !important;

            print-color-adjust:
                exact !important;
        }

        .sidebar,
        .topbar,
        .navbar,
        .report-toolbar {
            display:
                none !important;
        }

        .main-content,
        .content-wrapper {
            width:
                100% !important;

            max-width:
                none !important;

            margin:
                0 !important;

            padding:
                0 !important;
        }

        .report-page {
            width:
                100% !important;

            min-height:
                0 !important;

            margin:
                0 !important;

            padding:
                0 !important;

            background:
                #ffffff !important;

            display:
                flex !important;

            justify-content:
                center !important;

            align-items:
                flex-start !important;
        }

        .official-document {
            width:
                198mm !important;

            max-width:
                198mm !important;

            min-height:
                0 !important;

            margin:
                0 auto !important;

            padding:
                0 !important;

            border:
                none !important;

            border-radius:
                0 !important;

            box-shadow:
                none !important;

            overflow:
                visible !important;
        }

        .header-main {
            display:
                grid !important;

            grid-template-columns:
                45mm
                minmax(0, 1fr)
                35mm !important;

            gap:
                4mm !important;
        }

        .logo-area {
            width:
                45mm !important;

            height:
                20mm !important;

            display:
                flex !important;

            justify-content:
                center !important;

            overflow:
                visible !important;
        }

        .institution-logo {
            width:
                43mm !important;

            max-width:
                43mm !important;

            height:
                auto !important;

            max-height:
                19mm !important;

            object-fit:
                contain !important;
        }

        .patient-hero {
            display:
                grid !important;

            grid-template-columns:
                2fr
                .65fr
                1fr
                .75fr !important;
        }

        .personal-data {
            display:
                grid !important;

            grid-template-columns:
                1fr
                1fr !important;
        }

        .document-header,
        .header-main,
        .patient-hero,
        .document-section,
        .content-box,
        .document-notice,
        .signatures,
        .document-footer {
            break-inside:
                avoid !important;

            page-break-inside:
                avoid !important;
        }

    }

</style>


<div class="report-page">


    {{-- ====================================================== --}}
    {{-- TOOLBAR --}}
    {{-- ====================================================== --}}

    <div class="report-toolbar">


        <div>

            <h1 class="toolbar-title">
                Información general
            </h1>

            <p class="toolbar-subtitle">
                Vista previa del reporte institucional
            </p>

        </div>


        <div class="toolbar-actions">


            <a
                href="{{ route(
                    'reportes.paciente',
                    [
                        'paciente' => $paciente->id,
                        'periodo' => $periodo ?? 'todo',
                        'desde' => $desde ?? null,
                        'hasta' => $hasta ?? null,
                    ]
                ) }}"
                class="btn btn-light"
            >

                <i class="bi bi-arrow-left me-1"></i>

                Volver

            </a>


            {{-- PDF REAL CON DOMPDF --}}

            <a
                href="{{ route(
                    'reportes.informacion_general.pdf',
                    [
                        'paciente' => $paciente->id,
                        'periodo' => $periodo ?? 'todo',
                        'desde' => $desde ?? null,
                        'hasta' => $hasta ?? null,
                    ]
                ) }}"
                class="btn-report-action btn-pdf-report"
            >

                <i class="bi bi-file-earmark-pdf"></i>

                Descargar PDF

            </a>


            <button
                type="button"
                class="btn-report-action btn-print-report"
                onclick="window.print()"
            >

                <i class="bi bi-printer"></i>

                Imprimir

            </button>


        </div>


    </div>


    {{-- ====================================================== --}}
    {{-- DOCUMENTO --}}
    {{-- ====================================================== --}}

    <article class="official-document">


        {{-- ================================================== --}}
        {{-- MEMBRETE --}}
        {{-- ================================================== --}}

        <header class="document-header">


            <div class="header-main">


                <div class="logo-area">

                    <img
                        src="{{ asset('images/logo-vitalhome.png') }}"
                        alt="Logo Vital Homme"
                        class="institution-logo"
                    >

                </div>


                <div class="header-title-area">

                    <div class="document-overline">
                        Informe institucional
                    </div>


                    <h1 class="document-title">

                        Información general
                        del paciente

                    </h1>


                    <div class="document-description">

                        Registro de identificación,
                        información personal
                        y antecedentes generales

                    </div>

                </div>


                <div class="header-meta">


                    <div class="meta-item">

                        <span class="meta-label">
                            Código del documento
                        </span>

                        <span class="meta-value">
                            {{ $codigoReporte }}
                        </span>

                    </div>


                    <div class="meta-item">

                        <span class="meta-label">
                            Fecha de emisión
                        </span>

                        <span class="meta-value">
                            {{ now()->format('d/m/Y') }}
                        </span>

                    </div>


                    <div class="meta-item">

                        <span class="meta-label">
                            Hora
                        </span>

                        <span class="meta-value">
                            {{ now()->format('H:i') }}
                        </span>

                    </div>


                </div>


            </div>


            <div class="header-accent">

                <div class="accent-dark"></div>

                <div class="accent-light"></div>

                <div class="accent-main"></div>

            </div>


        </header>


        {{-- ================================================== --}}
        {{-- CONTENIDO --}}
        {{-- ================================================== --}}

        <div class="document-content">


            {{-- PACIENTE --}}

            <section class="patient-hero">


                <div class="patient-main">

                    <div class="small-label">
                        Paciente
                    </div>


                    <div class="patient-name">

                        {{ $paciente->nombre }}
                        {{ $paciente->apellido }}

                    </div>


                    <div class="patient-ci">

                        CI:
                        {{ $paciente->ci ?: 'No registrado' }}

                    </div>

                </div>


                <div class="hero-field">

                    <span class="small-label">
                        Edad
                    </span>

                    <span class="small-value">

                        {{ $edad !== null
                            ? $edad . ' años'
                            : '—'
                        }}

                    </span>

                </div>


                <div class="hero-field">

                    <span class="small-label">
                        Fecha de nacimiento
                    </span>

                    <span class="small-value">

                        {{ $paciente->fecha_nacimiento
                            ? $paciente->fecha_nacimiento->format('d/m/Y')
                            : '—'
                        }}

                    </span>

                </div>


                <div class="hero-field">

                    <span class="small-label">
                        Sexo
                    </span>

                    <span class="small-value">

                        {{ $paciente->sexo ?: '—' }}

                    </span>

                </div>


            </section>


            {{-- ================================================== --}}
            {{-- 1. DATOS PERSONALES --}}
            {{-- ================================================== --}}

            <section class="document-section">


                <div class="section-header">


                    <div class="section-icon">

                        <i class="bi bi-person-vcard"></i>

                    </div>


                    <div class="section-title-wrap">

                        <h2 class="section-title">
                            1. Datos personales
                        </h2>

                    </div>


                </div>


                <div class="personal-data">


                    <div class="data-column">


                        <div class="data-row">

                            <div class="data-label">
                                Nombre
                            </div>

                            <div class="data-value">

                                {{ $paciente->nombre
                                    ?: 'No registrado'
                                }}

                            </div>

                        </div>


                        <div class="data-row">

                            <div class="data-label">
                                Apellido
                            </div>

                            <div class="data-value">

                                {{ $paciente->apellido
                                    ?: 'No registrado'
                                }}

                            </div>

                        </div>


                        <div class="data-row">

                            <div class="data-label">
                                Carnet de identidad
                            </div>

                            <div class="data-value">

                                {{ $paciente->ci
                                    ?: 'No registrado'
                                }}

                            </div>

                        </div>


                        <div class="data-row">

                            <div class="data-label">
                                Sexo
                            </div>

                            <div class="data-value">

                                {{ $paciente->sexo
                                    ?: 'No registrado'
                                }}

                            </div>

                        </div>


                    </div>


                    <div class="data-column">


                        <div class="data-row">

                            <div class="data-label">
                                Fecha de nacimiento
                            </div>

                            <div class="data-value">

                                {{ $paciente->fecha_nacimiento
                                    ? $paciente->fecha_nacimiento->format('d/m/Y')
                                    : 'No registrada'
                                }}

                            </div>

                        </div>


                        <div class="data-row">

                            <div class="data-label">
                                Edad
                            </div>

                            <div class="data-value">

                                {{ $edad !== null
                                    ? $edad . ' años'
                                    : 'No registrada'
                                }}

                            </div>

                        </div>


                        <div class="data-row">

                            <div class="data-label">
                                Teléfono
                            </div>

                            <div class="data-value">

                                {{ $paciente->telefono
                                    ?: 'No registrado'
                                }}

                            </div>

                        </div>


                        <div class="data-row">

                            <div class="data-label">
                                Dirección
                            </div>

                            <div class="data-value">

                                {{ $paciente->direccion
                                    ?: 'No registrada'
                                }}

                            </div>

                        </div>


                    </div>


                </div>


            </section>


            {{-- ================================================== --}}
            {{-- 2. DIAGNÓSTICO --}}
            {{-- ================================================== --}}

            <section class="document-section">


                <div class="section-header">


                    <div class="section-icon">

                        <i class="bi bi-clipboard2-pulse"></i>

                    </div>


                    <div class="section-title-wrap">

                        <h2 class="section-title">
                            2. Diagnóstico registrado
                        </h2>

                    </div>


                </div>


                <div class="content-box">

                    {{ $paciente->diagnostico
                        ?: 'No se ha registrado un diagnóstico.'
                    }}

                </div>


            </section>


            {{-- ================================================== --}}
            {{-- 3. OBSERVACIONES --}}
            {{-- ================================================== --}}

            <section class="document-section">


                <div class="section-header">


                    <div class="section-icon">

                        <i class="bi bi-journal-text"></i>

                    </div>


                    <div class="section-title-wrap">

                        <h2 class="section-title">
                            3. Observaciones generales
                        </h2>

                    </div>


                </div>


                <div class="content-box">

                    {{ $paciente->observaciones
                        ?: 'No se han registrado observaciones generales.'
                    }}

                </div>


            </section>


            {{-- ================================================== --}}
            {{-- CONSTANCIA --}}
            {{-- ================================================== --}}

            <div class="document-notice">


                <div class="notice-icon">

                    <i class="bi bi-info-lg"></i>

                </div>


                <div>

                    El presente documento contiene la información
                    general registrada actualmente en el sistema
                    institucional de Vital Homme para el paciente
                    identificado en el encabezado.

                    Su contenido refleja los datos disponibles
                    al momento de su emisión.

                </div>


            </div>


            {{-- ================================================== --}}
            {{-- FIRMAS --}}
            {{-- ================================================== --}}

            <div class="signatures">


                <div class="signature">

                    <div class="signature-line"></div>

                    <div class="signature-name">
                        Responsable autorizado
                    </div>

                    <div class="signature-caption">
                        Nombre, firma y sello
                    </div>

                </div>


                <div class="signature">

                    <div class="signature-line"></div>

                    <div class="signature-name">
                        Dirección / Administración
                    </div>

                    <div class="signature-caption">
                        Firma y sello institucional
                    </div>

                </div>


            </div>


            {{-- ================================================== --}}
            {{-- PIE --}}
            {{-- ================================================== --}}

            <footer class="document-footer">


                <div class="footer-left">

                    <strong>
                        Vital Homme
                    </strong>

                    Centro de atención y cuidado
                    de adultos mayores

                    <br>

                    Documento generado mediante
                    el sistema institucional VitalHome.

                </div>


                <div class="footer-right">

                    <span class="footer-code">
                        {{ $codigoReporte }}
                    </span>

                    <br>

                    {{ now()->format('d/m/Y H:i') }}

                    <br>

                    Página 1 de 1

                </div>


            </footer>


            <div class="footer-accent"></div>


        </div>


    </article>


</div>


@endif

@endsection
