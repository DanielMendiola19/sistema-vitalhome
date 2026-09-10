@extends('layouts.app')

@section('title', 'Reporte general del paciente')

@section('content')

@php

    $edad = null;

    if ($paciente->fecha_nacimiento) {
        $edad = $paciente->fecha_nacimiento->age;
    }

    $codigoReporte =
        'VH-RG-' .
        str_pad(
            $paciente->id,
            5,
            '0',
            STR_PAD_LEFT
        ) .
        '-' .
        now()->format('Ymd');

    $textoPeriodo = 'Todo el historial';

    if ($periodo === 'rango' && $desde && $hasta) {

        $textoPeriodo =
            \Carbon\Carbon::parse($desde)->format('d/m/Y') .
            ' - ' .
            \Carbon\Carbon::parse($hasta)->format('d/m/Y');

    }

@endphp


<style>

    :root {
        --vh-green-dark: #047857;
        --vh-green: #059669;
        --vh-green-light: #10b981;
        --vh-lime: #84cc16;

        --vh-text: #0f172a;
        --vh-soft-text: #475569;
        --vh-muted: #94a3b8;

        --vh-border: #dbe3e8;
        --vh-soft: #f8fafc;
        --vh-green-soft: #ecfdf5;
    }


    /* ==========================================================
       PÁGINA
       ========================================================== */

    .report-page {
        min-height: calc(100vh - 70px);

        padding:
            28px
            20px
            45px;

        background:
            #f1f5f9;
    }


    /* ==========================================================
       TOOLBAR
       ========================================================== */

    .report-toolbar {
        width: 198mm;
        max-width: 100%;

        margin:
            0
            auto
            16px;

        display: flex;

        align-items: center;

        justify-content:
            space-between;

        gap:
            18px;
    }

    .toolbar-title {
        margin: 0;

        color:
            #0f172a;

        font-size:
            24px;

        font-weight:
            700;
    }

    .toolbar-subtitle {
        margin:
            3px
            0
            0;

        color:
            #64748b;

        font-size:
            12px;
    }

    .toolbar-actions {
        display:
            flex;

        align-items:
            center;

        gap:
            8px;

        flex-wrap:
            wrap;
    }

    .btn-report-action {
        min-height:
            38px;

        padding:
            9px
            15px;

        border:
            none;

        border-radius:
            8px;

        display:
            inline-flex;

        align-items:
            center;

        justify-content:
            center;

        gap:
            7px;

        font-size:
            13px;

        font-weight:
            600;

        text-decoration:
            none;

        cursor:
            pointer;

        transition:
            .2s ease;
    }

    .btn-pdf-report {
        background:
            #0f172a;

        color:
            #ffffff;
    }

    .btn-pdf-report:hover {
        background:
            #1e293b;

        color:
            #ffffff;
    }

    .btn-print-report {
        background:
            #10b981;

        color:
            #ffffff;
    }

    .btn-print-report:hover {
        background:
            #059669;

        color:
            #ffffff;
    }


    /* ==========================================================
       DOCUMENTO
       ========================================================== */

    .official-document {
        width:
            198mm;

        max-width:
            100%;

        margin:
            0 auto;

        padding:
            7mm;

        box-sizing:
            border-box;

        background:
            #ffffff;

        border:
            1px solid #d7dee5;

        border-radius:
            3px;

        box-shadow:
            0 8px 25px
            rgba(15, 23, 42, .08);

        font-family:
            Arial,
            Helvetica,
            sans-serif;

        color:
            #0f172a;
    }


    /* ==========================================================
       MEMBRETE
       ========================================================== */

    .document-header-table {
        width:
            100%;

        border-collapse:
            collapse;

        table-layout:
            fixed;
    }

    .document-header-table td {
        vertical-align:
            middle;
    }

    .logo-cell {
        width:
            25%;

        padding-right:
            10px;
    }

    .institution-logo {
        display:
            block;

        width:
            43mm;

        max-width:
            100%;

        height:
            auto;
    }

    .title-cell {
        width:
            50%;

        padding:
            0
            14px;

        text-align:
            center;

        border-left:
            1px solid #cbd5e1;

        border-right:
            1px solid #cbd5e1;
    }

    .document-overline {
        margin-bottom:
            5px;

        color:
            #64748b;

        font-size:
            6px;

        font-weight:
            700;

        letter-spacing:
            1px;

        text-transform:
            uppercase;
    }

    .document-title {
        margin:
            0;

        color:
            #0f172a;

        font-size:
            15px;

        font-weight:
            800;

        line-height:
            1.15;

        text-transform:
            uppercase;
    }

    .document-subtitle {
        margin-top:
            5px;

        color:
            #64748b;

        font-size:
            6.5px;

        line-height:
            1.3;
    }

    .meta-cell {
        width:
            25%;

        padding-left:
            12px;
    }

    .meta-block {
        padding:
            4px
            0;

        border-bottom:
            1px solid #e2e8f0;
    }

    .meta-block:last-child {
        border-bottom:
            none;
    }

    .meta-label {
        color:
            #94a3b8;

        font-size:
            5.5px;

        font-weight:
            700;

        text-transform:
            uppercase;
    }

    .meta-value {
        margin-top:
            2px;

        color:
            #0f172a;

        font-size:
            7px;

        font-weight:
            700;
    }


    /* ==========================================================
       LÍNEA CORPORATIVA
       ========================================================== */

    .brand-line {
        width:
            100%;

        height:
            4px;

        margin-top:
            12px;

        border-collapse:
            collapse;
    }

    .brand-line td {
        height:
            4px;

        padding:
            0;
    }

    .brand-dark {
        width:
            17%;

        background:
            #047857;
    }

    .brand-lime {
        width:
            11%;

        background:
            #84cc16;
    }

    .brand-green {
        background:
            #10b981;
    }


    /* ==========================================================
       PACIENTE
       ========================================================== */

    .patient-box {
        margin-top:
            13px;

        padding:
            10px
            12px;

        background:
            #f0fdf4;

        border-left:
            4px solid #10b981;
    }

    .patient-table {
        width:
            100%;

        border-collapse:
            collapse;

        table-layout:
            fixed;
    }

    .patient-table td {
        vertical-align:
            middle;
    }

    .patient-main {
        width:
            35%;
    }

    .patient-field {
        width:
            21.66%;

        padding-left:
            12px;

        border-left:
            1px solid #dbe4ea;
    }

    .small-label {
        display:
            block;

        color:
            #94a3b8;

        font-size:
            5.5px;

        font-weight:
            700;

        text-transform:
            uppercase;
    }

    .patient-name {
        margin-top:
            3px;

        color:
            #0f172a;

        font-size:
            14px;

        font-weight:
            800;
    }

    .patient-ci {
        margin-top:
            3px;

        color:
            #475569;

        font-size:
            7px;
    }

    .small-value {
        display:
            block;

        margin-top:
            3px;

        color:
            #0f172a;

        font-size:
            7.5px;

        font-weight:
            700;
    }


    /* ==========================================================
       PERÍODO
       ========================================================== */

    .period-box {
        margin-top:
            10px;

        padding:
            7px
            10px;

        border:
            1px solid #d1fae5;

        background:
            #ecfdf5;

        color:
            #166534;

        font-size:
            7px;

        font-weight:
            700;
    }


    /* ==========================================================
       SECCIONES
       ========================================================== */

    .report-section {
        margin-top:
            13px;
    }

    .section-header-table {
        width:
            100%;

        margin-bottom:
            8px;

        border-collapse:
            collapse;
    }

    .section-number {
        width:
            28px;

        padding:
            6px
            0;

        background:
            #047857;

        color:
            #ffffff;

        text-align:
            center;

        font-size:
            9px;

        font-weight:
            700;
    }

    .section-title {
        padding:
            6px
            10px;

        background:
            #ecfdf5;

        color:
            #1e293b;

        font-size:
            8px;

        font-weight:
            700;

        text-transform:
            uppercase;
    }


    /* ==========================================================
       INFORMACIÓN GENERAL
       ========================================================== */

    .info-table {
        width:
            100%;

        border-collapse:
            collapse;

        table-layout:
            fixed;

        border:
            1px solid #dbe3e8;
    }

    .info-table td {
        width:
            25%;

        padding:
            7px
            8px;

        border-right:
            1px solid #eef2f7;

        border-bottom:
            1px solid #eef2f7;

        vertical-align:
            top;
    }

    .info-table td:last-child {
        border-right:
            none;
    }

    .info-label {
        color:
            #94a3b8;

        font-size:
            5px;

        font-weight:
            700;

        text-transform:
            uppercase;
    }

    .info-value {
        margin-top:
            3px;

        color:
            #0f172a;

        font-size:
            6.5px;

        font-weight:
            700;

        line-height:
            1.35;
    }

    .text-card {
        margin-top:
            8px;

        padding:
            8px
            10px;

        border:
            1px solid #dbe3e8;

        background:
            #fcfdfe;
    }

    .text-card-title {
        margin-bottom:
            4px;

        color:
            #64748b;

        font-size:
            5.5px;

        font-weight:
            700;

        text-transform:
            uppercase;
    }

    .text-card-value {
        margin:
            0;

        color:
            #334155;

        font-size:
            6.5px;

        line-height:
            1.5;

        white-space:
            pre-line;
    }


    /* ==========================================================
       TRATAMIENTOS
       ========================================================== */

    .treatment-card {
        margin-bottom:
            10px;

        border:
            1px solid #dbe3e8;

        page-break-inside:
            avoid;

        break-inside:
            avoid;
    }

    .treatment-header-table {
        width:
            100%;

        border-collapse:
            collapse;

        background:
            #f8fafc;

        border-bottom:
            1px solid #dbe3e8;
    }

    .treatment-header-table td {
        padding:
            7px
            9px;

        vertical-align:
            middle;
    }

    .treatment-name-cell {
        width:
            78%;
    }

    .treatment-status-cell {
        width:
            22%;

        text-align:
            right;
    }

    .medicine-name {
        color:
            #0f172a;

        font-size:
            8.5px;

        font-weight:
            800;
    }

    .medicine-extra {
        margin-top:
            2px;

        color:
            #64748b;

        font-size:
            6px;
    }

    .status {
        display:
            inline-block;

        padding:
            3px
            7px;

        font-size:
            6px;

        font-weight:
            700;

        text-transform:
            uppercase;
    }

    .status-activo {
        background:
            #ecfdf5;

        color:
            #047857;
    }

    .status-suspendido {
        background:
            #fff7ed;

        color:
            #c2410c;
    }

    .status-finalizado {
        background:
            #f1f5f9;

        color:
            #475569;
    }

    .status-default {
        background:
            #f1f5f9;

        color:
            #475569;
    }

    .treatment-data-table {
        width:
            100%;

        border-collapse:
            collapse;

        table-layout:
            fixed;
    }

    .treatment-data-table td {
        width:
            25%;

        padding:
            6px
            7px;

        border-right:
            1px solid #eef2f7;

        border-bottom:
            1px solid #eef2f7;

        vertical-align:
            top;
    }

    .treatment-data-table td:last-child {
        border-right:
            none;
    }

    .treatment-label {
        color:
            #94a3b8;

        font-size:
            5px;

        font-weight:
            700;

        text-transform:
            uppercase;
    }

    .treatment-value {
        margin-top:
            2px;

        color:
            #334155;

        font-size:
            6.5px;

        font-weight:
            700;
    }

    .schedule {
        padding:
            7px
            9px;

        border-bottom:
            1px solid #eef2f7;
    }

    .schedule-pill {
        display:
            inline-block;

        margin-right:
            5px;

        padding:
            3px
            6px;

        border:
            1px solid #d1fae5;

        background:
            #f0fdf4;

        color:
            #166534;

        font-size:
            6px;

        font-weight:
            700;
    }

    .treatment-indications {
        padding:
            7px
            9px;

        background:
            #fcfdfe;
    }

    .treatment-indications-label {
        margin-bottom:
            3px;

        color:
            #64748b;

        font-size:
            5.5px;

        font-weight:
            700;

        text-transform:
            uppercase;
    }

    .treatment-indications-text {
        margin:
            0;

        color:
            #334155;

        font-size:
            6.5px;

        line-height:
            1.45;

        white-space:
            pre-line;
    }


    /* ==========================================================
       HISTORIA CLÍNICA
       ========================================================== */

    .clinical-item {
        margin-bottom:
            9px;

        border:
            1px solid #dbe3e8;

        page-break-inside:
            avoid;

        break-inside:
            avoid;
    }

    .clinical-header-table {
        width:
            100%;

        border-collapse:
            collapse;

        table-layout:
            fixed;

        background:
            #f8fafc;

        border-bottom:
            1px solid #dbe3e8;
    }

    .clinical-header-table td {
        padding:
            7px
            9px;
    }

    .clinical-date {
        width:
            58%;

        color:
            #0f172a;

        font-size:
            6.5px;

        font-weight:
            700;
    }

    .clinical-user {
        width:
            42%;

        color:
            #64748b;

        font-size:
            6px;

        text-align:
            right;
    }

    .clinical-body {
        padding:
            8px
            10px;
    }

    .clinical-text {
        margin:
            0;

        color:
            #334155;

        font-size:
            6.5px;

        line-height:
            1.5;

        white-space:
            pre-line;
    }


    /* ==========================================================
       SIGNOS VITALES
       ========================================================== */

    .table-wrapper {
        width:
            100%;

        overflow-x:
            auto;

        border:
            1px solid #dbe3e8;
    }

    .report-table {
        width:
            100%;

        border-collapse:
            collapse;

        table-layout:
            fixed;
    }

    .report-table th {
        padding:
            7px
            6px;

        background:
            #f8fafc;

        border-right:
            1px solid #e2e8f0;

        border-bottom:
            1px solid #dbe3e8;

        color:
            #64748b;

        font-size:
            5.5px;

        font-weight:
            700;

        text-align:
            left;

        text-transform:
            uppercase;
    }

    .report-table th:last-child {
        border-right:
            none;
    }

    .report-table td {
        padding:
            7px
            6px;

        border-right:
            1px solid #eef2f7;

        border-bottom:
            1px solid #eef2f7;

        color:
            #334155;

        font-size:
            6px;

        vertical-align:
            middle;
    }

    .report-table td:last-child {
        border-right:
            none;
    }

    .report-table tbody tr:last-child td {
        border-bottom:
            none;
    }


    /* ==========================================================
       VACÍO
       ========================================================== */

    .empty-box {
        padding:
            20px;

        border:
            1px solid #dbe3e8;

        background:
            #f8fafc;

        color:
            #64748b;

        font-size:
            7px;

        text-align:
            center;
    }


    /* ==========================================================
       AVISO
       ========================================================== */

    .document-notice {
        margin-top:
            13px;

        padding:
            8px
            10px;

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

        page-break-inside:
            avoid;
    }


    /* ==========================================================
       FIRMAS
       ========================================================== */

    .signature-table {
        width:
            82%;

        margin:
            35px
            auto
            0;

        border-collapse:
            collapse;

        table-layout:
            fixed;

        page-break-inside:
            avoid;
    }

    .signature-table td {
        width:
            50%;

        padding:
            0
            22px;

        text-align:
            center;
    }

    .signature-box {
        padding-top:
            5px;

        border-top:
            1px solid #475569;
    }

    .signature-name {
        color:
            #0f172a;

        font-size:
            6.8px;

        font-weight:
            700;
    }

    .signature-caption {
        margin-top:
            2px;

        color:
            #94a3b8;

        font-size:
            5.5px;
    }


    /* ==========================================================
       FOOTER
       ========================================================== */

    .footer-table {
        width:
            100%;

        margin-top:
            18px;

        padding-top:
            7px;

        border-top:
            1px solid #cbd5e1;

        border-collapse:
            collapse;

        page-break-inside:
            avoid;
    }

    .footer-table td {
        vertical-align:
            bottom;
    }

    .footer-left {
        width:
            70%;

        color:
            #64748b;

        font-size:
            5.5px;

        line-height:
            1.4;
    }

    .footer-left strong {
        color:
            #1e293b;

        font-size:
            6.5px;
    }

    .footer-right {
        width:
            30%;

        color:
            #64748b;

        font-size:
            5.5px;

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

    .footer-green-line {
        width:
            100%;

        height:
            5px;

        margin-top:
            9px;

        background:
            #059669;
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

            flex-direction:
                column;

            align-items:
                flex-start;
        }

        .official-document {
            width:
                100%;

            padding:
                18px;
        }

        .document-header-table,
        .document-header-table tbody,
        .document-header-table tr,
        .document-header-table td {
            display:
                block;

            width:
                100%;
        }

        .logo-cell {
            padding:
                0;

            text-align:
                center;
        }

        .institution-logo {
            margin:
                0 auto;
        }

        .title-cell {
            margin-top:
                15px;

            padding:
                15px
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

        .meta-cell {
            padding:
                10px
                0
                0;
        }

        .report-table {
            min-width:
                760px;
        }

    }


    /* ==========================================================
       IMPRESIÓN
       ========================================================== */

    @media print {

        @page {
            size:
                letter portrait;

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

        .desktop-sidebar,
        .mobile-header,
        .offcanvas,
        .report-toolbar {
            display:
                none !important;
        }

        .app-container {
            display:
                block !important;

            width:
                100% !important;

            margin:
                0 !important;

            padding:
                0 !important;
        }

        .main-content {
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
        }

        .official-document {
            width:
                100% !important;

            max-width:
                none !important;

            margin:
                0 !important;

            padding:
                0 !important;

            border:
                none !important;

            border-radius:
                0 !important;

            box-shadow:
                none !important;
        }

        .document-header-table {
            display:
                table !important;

            width:
                100% !important;
        }

        .document-header-table tbody {
            display:
                table-row-group !important;
        }

        .document-header-table tr {
            display:
                table-row !important;
        }

        .document-header-table td {
            display:
                table-cell !important;
        }

        .logo-cell {
            width:
                25% !important;
        }

        .title-cell {
            width:
                50% !important;

            padding:
                0
                14px !important;

            border-left:
                1px solid #cbd5e1 !important;

            border-right:
                1px solid #cbd5e1 !important;

            border-top:
                none !important;

            border-bottom:
                none !important;
        }

        .meta-cell {
            width:
                25% !important;

            padding-left:
                12px !important;
        }

        .table-wrapper {
            overflow:
                visible !important;
        }

        .report-table {
            width:
                100% !important;

            min-width:
                0 !important;
        }

        .treatment-card,
        .clinical-item,
        .report-table tr,
        .document-notice,
        .signature-table,
        .footer-table {
            break-inside:
                avoid;

            page-break-inside:
                avoid;
        }

    }


    /* ==========================================================
       DOMPDF
       ========================================================== */

    @if($modoPdf ?? false)

        @page {
            size:
                letter portrait;

            margin:
                18px
                24px
                24px;
        }

        body {
            margin:
                0;

            padding:
                0;

            font-family:
                DejaVu Sans,
                Arial,
                sans-serif;

            font-size:
                8px;
        }

        .report-page {
            min-height:
                0;

            margin:
                0;

            padding:
                0;

            background:
                #ffffff;
        }

        .official-document {
            width:
                100%;

            max-width:
                none;

            margin:
                0;

            padding:
                0;

            border:
                none;

            border-radius:
                0;

            box-shadow:
                none;
        }

        .table-wrapper {
            overflow:
                visible;
        }

        .report-table {
            width:
                100%;
        }

    @endif

</style>


<div class="report-page">


    {{-- ====================================================== --}}
    {{-- TOOLBAR --}}
    {{-- ====================================================== --}}

    @if(!($modoPdf ?? false))

        <div class="report-toolbar">

            <div>

                <h1 class="toolbar-title">
                    Reporte general
                </h1>

                <p class="toolbar-subtitle">
                    Informe consolidado del paciente
                </p>

            </div>


            <div class="toolbar-actions">

                <a
                    href="{{ route(
                        'reportes.paciente',
                        [
                            'paciente' => $paciente->id,
                            'periodo' => $periodo,
                            'desde' => $desde,
                            'hasta' => $hasta,
                        ]
                    ) }}"
                    class="btn btn-light"
                >

                    <i class="bi bi-arrow-left me-1"></i>

                    Volver

                </a>


                <a
                    href="{{ route(
                        'reportes.general.pdf',
                        [
                            'paciente' => $paciente->id,
                            'periodo' => $periodo,
                            'desde' => $desde,
                            'hasta' => $hasta,
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

    @endif


    {{-- ====================================================== --}}
    {{-- DOCUMENTO --}}
    {{-- ====================================================== --}}

    <div class="official-document">


        {{-- ================================================== --}}
        {{-- MEMBRETE --}}
        {{-- ================================================== --}}

        <table class="document-header-table">

            <tr>


                <td class="logo-cell">

                    @if($modoPdf ?? false)

                        @if(!empty($logoBase64))

                            <img
                                src="{{ $logoBase64 }}"
                                alt="Vital Homme"
                                class="institution-logo"
                            >

                        @endif

                    @else

                        <img
                            src="{{ asset('images/logo-vitalhome.png') }}"
                            alt="Vital Homme"
                            class="institution-logo"
                        >

                    @endif

                </td>


                <td class="title-cell">

                    <div class="document-overline">
                        Informe institucional
                    </div>

                    <div class="document-title">
                        Reporte general del paciente
                    </div>

                    <div class="document-subtitle">

                        Informe consolidado de información general,
                        tratamientos, historia clínica y signos vitales

                    </div>

                </td>


                <td class="meta-cell">


                    <div class="meta-block">

                        <div class="meta-label">
                            Código del documento
                        </div>

                        <div class="meta-value">
                            {{ $codigoReporte }}
                        </div>

                    </div>


                    <div class="meta-block">

                        <div class="meta-label">
                            Fecha de emisión
                        </div>

                        <div class="meta-value">
                            {{ now()->format('d/m/Y') }}
                        </div>

                    </div>


                    <div class="meta-block">

                        <div class="meta-label">
                            Hora
                        </div>

                        <div class="meta-value">
                            {{ now()->format('H:i') }}
                        </div>

                    </div>


                </td>


            </tr>

        </table>


        {{-- ================================================== --}}
        {{-- LÍNEA CORPORATIVA --}}
        {{-- ================================================== --}}

        <table class="brand-line">

            <tr>

                <td class="brand-dark"></td>

                <td class="brand-lime"></td>

                <td class="brand-green"></td>

            </tr>

        </table>


        {{-- ================================================== --}}
        {{-- PACIENTE --}}
        {{-- ================================================== --}}

        <div class="patient-box">

            <table class="patient-table">

                <tr>


                    <td class="patient-main">

                        <span class="small-label">
                            Paciente
                        </span>

                        <div class="patient-name">

                            {{ $paciente->nombre }}
                            {{ $paciente->apellido }}

                        </div>

                        <div class="patient-ci">

                            CI:
                            {{ $paciente->ci ?: 'No registrado' }}

                        </div>

                    </td>


                    <td class="patient-field">

                        <span class="small-label">
                            Edad
                        </span>

                        <span class="small-value">

                            {{ $edad !== null
                                ? $edad . ' años'
                                : '—'
                            }}

                        </span>

                    </td>


                    <td class="patient-field">

                        <span class="small-label">
                            Diagnóstico
                        </span>

                        <span class="small-value">

                            {{ $paciente->diagnostico ?: '—' }}

                        </span>

                    </td>


                    <td class="patient-field">

                        <span class="small-label">
                            Estado del informe
                        </span>

                        <span class="small-value">
                            Consolidado
                        </span>

                    </td>


                </tr>

            </table>

        </div>


        {{-- ================================================== --}}
        {{-- PERÍODO --}}
        {{-- ================================================== --}}

        <div class="period-box">

            Período del reporte:
            {{ $textoPeriodo }}

        </div>


        {{-- ================================================== --}}
        {{-- 1. INFORMACIÓN GENERAL --}}
        {{-- ================================================== --}}

        <div class="report-section">

            <table class="section-header-table">

                <tr>

                    <td class="section-number">
                        1
                    </td>

                    <td class="section-title">
                        Información general
                    </td>

                </tr>

            </table>


            <table class="info-table">


                <tr>


                    <td>

                        <div class="info-label">
                            Nombre
                        </div>

                        <div class="info-value">
                            {{ $paciente->nombre ?: '—' }}
                        </div>

                    </td>


                    <td>

                        <div class="info-label">
                            Apellido
                        </div>

                        <div class="info-value">
                            {{ $paciente->apellido ?: '—' }}
                        </div>

                    </td>


                    <td>

                        <div class="info-label">
                            Carnet
                        </div>

                        <div class="info-value">
                            {{ $paciente->ci ?: '—' }}
                        </div>

                    </td>


                    <td>

                        <div class="info-label">
                            Sexo
                        </div>

                        <div class="info-value">
                            {{ $paciente->sexo ?: '—' }}
                        </div>

                    </td>


                </tr>


                <tr>


                    <td>

                        <div class="info-label">
                            Fecha de nacimiento
                        </div>

                        <div class="info-value">

                            {{ $paciente->fecha_nacimiento
                                ? $paciente->fecha_nacimiento
                                    ->format('d/m/Y')
                                : '—'
                            }}

                        </div>

                    </td>


                    <td>

                        <div class="info-label">
                            Edad
                        </div>

                        <div class="info-value">

                            {{ $edad !== null
                                ? $edad . ' años'
                                : '—'
                            }}

                        </div>

                    </td>


                    <td>

                        <div class="info-label">
                            Teléfono
                        </div>

                        <div class="info-value">
                            {{ $paciente->telefono ?: '—' }}
                        </div>

                    </td>


                    <td>

                        <div class="info-label">
                            Dirección
                        </div>

                        <div class="info-value">
                            {{ $paciente->direccion ?: '—' }}
                        </div>

                    </td>


                </tr>


            </table>


            <div class="text-card">

                <div class="text-card-title">
                    Diagnóstico
                </div>

                <p class="text-card-value">

                    {{ $paciente->diagnostico
                        ?: 'No se ha registrado un diagnóstico.'
                    }}

                </p>

            </div>


            <div class="text-card">

                <div class="text-card-title">
                    Observaciones generales
                </div>

                <p class="text-card-value">

                    {{ $paciente->observaciones
                        ?: 'No se han registrado observaciones generales.'
                    }}

                </p>

            </div>


        </div>


        {{-- ================================================== --}}
        {{-- 2. KARDEX --}}
        {{-- ================================================== --}}

        <div class="report-section">


            <table class="section-header-table">

                <tr>

                    <td class="section-number">
                        2
                    </td>

                    <td class="section-title">

                        Kardex / Tratamientos
                        ({{ $tratamientos->count() }})

                    </td>

                </tr>

            </table>


            @forelse($tratamientos as $tratamiento)


                @php

                    $estadoClase = match($tratamiento->estado) {
                        'activo' => 'status-activo',
                        'suspendido' => 'status-suspendido',
                        'finalizado' => 'status-finalizado',
                        default => 'status-default',
                    };

                @endphp


                <div class="treatment-card">


                    <table class="treatment-header-table">

                        <tr>


                            <td class="treatment-name-cell">

                                <div class="medicine-name">

                                    {{ $tratamiento->medicamento?->nombre
                                        ?: 'Medicamento no disponible'
                                    }}

                                </div>


                                <div class="medicine-extra">

                                    @if($tratamiento->medicamento?->principio_activo)

                                        {{ $tratamiento
                                            ->medicamento
                                            ->principio_activo
                                        }}

                                    @endif


                                    @if($tratamiento->medicamento?->concentracion)

                                        @if($tratamiento->medicamento?->principio_activo)
                                            ·
                                        @endif

                                        {{ $tratamiento
                                            ->medicamento
                                            ->concentracion
                                        }}

                                    @endif


                                    @if($tratamiento->medicamento?->presentacion)

                                        @if(
                                            $tratamiento->medicamento?->principio_activo
                                            ||
                                            $tratamiento->medicamento?->concentracion
                                        )
                                            ·
                                        @endif

                                        {{ $tratamiento
                                            ->medicamento
                                            ->presentacion
                                        }}

                                    @endif

                                </div>

                            </td>


                            <td class="treatment-status-cell">

                                <span class="status {{ $estadoClase }}">

                                    {{ $tratamiento->estado
                                        ?: 'Sin estado'
                                    }}

                                </span>

                            </td>


                        </tr>

                    </table>


                    <table class="treatment-data-table">


                        <tr>


                            <td>

                                <div class="treatment-label">
                                    Dosis
                                </div>

                                <div class="treatment-value">
                                    {{ $tratamiento->dosis ?: '—' }}
                                </div>

                            </td>


                            <td>

                                <div class="treatment-label">
                                    Frecuencia
                                </div>

                                <div class="treatment-value">
                                    {{ $tratamiento->frecuencia ?: '—' }}
                                </div>

                            </td>


                            <td>

                                <div class="treatment-label">
                                    Vía
                                </div>

                                <div class="treatment-value">
                                    {{ $tratamiento->via_administracion ?: '—' }}
                                </div>

                            </td>


                            <td>

                                <div class="treatment-label">
                                    Unidad
                                </div>

                                <div class="treatment-value">

                                    {{ $tratamiento
                                        ->medicamento
                                        ?->unidad_medida
                                        ?: '—'
                                    }}

                                </div>

                            </td>


                        </tr>


                        <tr>


                            <td>

                                <div class="treatment-label">
                                    Inicio
                                </div>

                                <div class="treatment-value">

                                    {{ $tratamiento->fecha_inicio
                                        ? $tratamiento->fecha_inicio
                                            ->format('d/m/Y')
                                        : '—'
                                    }}

                                </div>

                            </td>


                            <td>

                                <div class="treatment-label">
                                    Fin
                                </div>

                                <div class="treatment-value">

                                    {{ $tratamiento->fecha_fin
                                        ? $tratamiento->fecha_fin
                                            ->format('d/m/Y')
                                        : 'Sin fecha'
                                    }}

                                </div>

                            </td>


                            <td>

                                <div class="treatment-label">
                                    Presentación
                                </div>

                                <div class="treatment-value">

                                    {{ $tratamiento
                                        ->medicamento
                                        ?->presentacion
                                        ?: '—'
                                    }}

                                </div>

                            </td>


                            <td>

                                <div class="treatment-label">
                                    Estado
                                </div>

                                <div class="treatment-value">

                                    {{ ucfirst(
                                        $tratamiento->estado
                                        ?: '—'
                                    ) }}

                                </div>

                            </td>


                        </tr>


                    </table>


                    <div class="schedule">

                        @if($tratamiento->hora_tm)

                            <span class="schedule-pill">

                                TM:
                                {{ $tratamiento->hora_tm->format('H:i') }}

                            </span>

                        @endif


                        @if($tratamiento->hora_tt)

                            <span class="schedule-pill">

                                TT:
                                {{ $tratamiento->hora_tt->format('H:i') }}

                            </span>

                        @endif


                        @if($tratamiento->hora_tn)

                            <span class="schedule-pill">

                                TN:
                                {{ $tratamiento->hora_tn->format('H:i') }}

                            </span>

                        @endif


                        @if(
                            !$tratamiento->hora_tm
                            &&
                            !$tratamiento->hora_tt
                            &&
                            !$tratamiento->hora_tn
                        )

                            <span class="treatment-value">

                                No se registraron horarios específicos.

                            </span>

                        @endif

                    </div>


                    <div class="treatment-indications">

                        <div class="treatment-indications-label">
                            Indicaciones
                        </div>

                        <p class="treatment-indications-text">

                            {{ $tratamiento->indicaciones
                                ?: 'No se registraron indicaciones adicionales.'
                            }}

                        </p>

                    </div>


                </div>


            @empty


                <div class="empty-box">

                    No existen tratamientos relacionados
                    con el período seleccionado.

                </div>


            @endforelse


        </div>


        {{-- ================================================== --}}
        {{-- 3. HISTORIA CLÍNICA --}}
        {{-- ================================================== --}}

        <div class="report-section">


            <table class="section-header-table">

                <tr>

                    <td class="section-number">
                        3
                    </td>

                    <td class="section-title">

                        Historia clínica
                        ({{ $observaciones->count() }})

                    </td>

                </tr>

            </table>


            @forelse($observaciones as $observacion)


                <div class="clinical-item">


                    <table class="clinical-header-table">

                        <tr>


                            <td class="clinical-date">

                                Fecha y hora:

                                {{ $observacion->created_at
                                    ? $observacion->created_at
                                        ->format('d/m/Y H:i')
                                    : 'Fecha no disponible'
                                }}

                            </td>


                            <td class="clinical-user">

                                Registrado por:

                                @if($observacion->usuario)

                                    Usuario #{{ $observacion->usuario->id }}

                                @elseif($observacion->usuario_id)

                                    Usuario #{{ $observacion->usuario_id }}

                                @else

                                    Usuario no disponible

                                @endif

                            </td>


                        </tr>

                    </table>


                    <div class="clinical-body">

                        <p class="clinical-text">

                            {{ $observacion->observacion
                                ?: 'Sin contenido registrado.'
                            }}

                        </p>

                    </div>


                </div>


            @empty


                <div class="empty-box">

                    No existen observaciones clínicas
                    dentro del período seleccionado.

                </div>


            @endforelse


        </div>


        {{-- ================================================== --}}
        {{-- 4. SIGNOS VITALES --}}
        {{-- ================================================== --}}

        <div class="report-section">


            <table class="section-header-table">

                <tr>

                    <td class="section-number">
                        4
                    </td>

                    <td class="section-title">

                        Signos vitales
                        ({{ $signosVitales->count() }})

                    </td>

                </tr>

            </table>


            @if($signosVitales->isNotEmpty())


                <div class="table-wrapper">

                    <table class="report-table">


                        <thead>

                            <tr>

                                <th>
                                    Fecha / hora
                                </th>

                                <th>
                                    Presión arterial
                                </th>

                                <th>
                                    F. cardíaca
                                </th>

                                <th>
                                    F. respiratoria
                                </th>

                                <th>
                                    Temperatura
                                </th>

                                <th>
                                    SpO₂
                                </th>

                            </tr>

                        </thead>


                        <tbody>


                            @foreach($signosVitales as $signo)


                                <tr>


                                    <td>

                                        {{ $signo->fecha_registro
                                            ? $signo->fecha_registro
                                                ->format('d/m/Y H:i')
                                            : '—'
                                        }}

                                    </td>


                                    <td>

                                        @if($signo->presion_arterial)

                                            {{ $signo->presion_arterial }}
                                            mmHg

                                        @else

                                            —

                                        @endif

                                    </td>


                                    <td>

                                        @if($signo->frecuencia_cardiaca !== null)

                                            {{ $signo->frecuencia_cardiaca }}
                                            lpm

                                        @else

                                            —

                                        @endif

                                    </td>


                                    <td>

                                        @if($signo->frecuencia_respiratoria !== null)

                                            {{ $signo->frecuencia_respiratoria }}
                                            rpm

                                        @else

                                            —

                                        @endif

                                    </td>


                                    <td>

                                        @if($signo->temperatura !== null)

                                            {{ $signo->temperatura }}
                                            °C

                                        @else

                                            —

                                        @endif

                                    </td>


                                    <td>

                                        @if($signo->spo2 !== null)

                                            {{ $signo->spo2 }}
                                            %

                                        @else

                                            —

                                        @endif

                                    </td>


                                </tr>


                            @endforeach


                        </tbody>


                    </table>

                </div>


            @else


                <div class="empty-box">

                    No existen registros de signos vitales
                    dentro del período seleccionado.

                </div>


            @endif


        </div>


        {{-- ================================================== --}}
        {{-- CONSTANCIA --}}
        {{-- ================================================== --}}

        <div class="document-notice">

            El presente documento consolida la información
            general, tratamientos, observaciones clínicas
            y registros de signos vitales disponibles en
            el sistema institucional de Vital Homme para
            el paciente identificado en el encabezado.

        </div>


        {{-- ================================================== --}}
        {{-- FIRMAS --}}
        {{-- ================================================== --}}

        <table class="signature-table">

            <tr>


                <td>

                    <div class="signature-box">

                        <div class="signature-name">
                            Responsable autorizado
                        </div>

                        <div class="signature-caption">
                            Nombre, firma y sello
                        </div>

                    </div>

                </td>


                <td>

                    <div class="signature-box">

                        <div class="signature-name">
                            Dirección / Administración
                        </div>

                        <div class="signature-caption">
                            Firma y sello institucional
                        </div>

                    </div>

                </td>


            </tr>

        </table>


        {{-- ================================================== --}}
        {{-- FOOTER --}}
        {{-- ================================================== --}}

        <table class="footer-table">

            <tr>


                <td class="footer-left">

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


                <td class="footer-right">

                    <span class="footer-code">
                        {{ $codigoReporte }}
                    </span>

                    <br>

                    {{ now()->format('d/m/Y H:i') }}

                </td>


            </tr>

        </table>


        <div class="footer-green-line"></div>


    </div>


</div>

@endsection
