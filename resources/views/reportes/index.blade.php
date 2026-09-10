@extends('layouts.app')

@section('title', 'Reportes')

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

    .search-form {
        max-width: 520px;
        margin-bottom: 18px;
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

    .reports-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        box-shadow: 0 2px 8px rgba(15,23,42,.04);
        overflow: hidden;
    }

    .reports-table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .reports-table {
        width: 100%;
        border-collapse: collapse;
    }

    .reports-table thead {
        background: #f8fafc;
    }

    .reports-table th {
        padding: 14px 18px;
        color: #64748b;
        font-size: 11px;
        font-weight: 700;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
    }

    .reports-table td {
        padding: 17px 18px;
        border-bottom: 1px solid #f1f5f9;
        color: #475569;
        font-size: 14px;
        vertical-align: middle;
    }

    .reports-table tbody tr:hover {
        background: #f8fafc;
    }

    .patient-name {
        color: #0f172a;
        font-weight: 700;
    }

    .patient-ci {
        color: #94a3b8;
        font-size: 12px;
        margin-top: 3px;
    }

    .report-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
        border-radius: 999px;
        background: #ecfdf5;
        color: #059669;
        font-size: 12px;
        font-weight: 700;
    }

    .report-link {
        color: #059669;
        font-weight: 700;
        text-decoration: none;
        white-space: nowrap;
    }

    .report-link:hover {
        color: #047857;
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

    .pagination-wrapper {
        margin-top: 20px;
    }

    @media (max-width: 768px) {

        .reports-page {
            padding: 20px 15px;
        }

        .reports-header {
            align-items: flex-start;
            flex-direction: column;
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
                Reportes
            </h1>

            <p class="reports-subtitle">
                Consulta y generación de reportes por paciente
            </p>

        </div>

    </div>


    {{-- ====================================================== --}}
    {{-- BÚSQUEDA --}}
    {{-- ====================================================== --}}

    <form
        method="GET"
        action="{{ route('reportes.index') }}"
        class="search-form"
    >

        <div class="search-wrapper">

            <i class="bi bi-search"></i>

            <input
                type="text"
                name="buscar"
                value="{{ $buscar ?? '' }}"
                class="search-input"
                placeholder="Buscar paciente por nombre, apellido o carnet..."
            >

        </div>

    </form>


    {{-- ====================================================== --}}
    {{-- TABLA --}}
    {{-- ====================================================== --}}

    <div class="reports-card">

        @if($pacientes->count())

            <div class="reports-table-wrapper">

                <table class="reports-table">

                    <thead>

                        <tr>

                            <th>
                                PACIENTE
                            </th>

                            <th>
                                CARNET
                            </th>

                            <th>
                                ACCIONES
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @foreach($pacientes as $paciente)

                            <tr>

                                {{-- PACIENTE --}}

                                <td>

                                    <div class="patient-name">

                                        {{ $paciente->nombre }}
                                        {{ $paciente->apellido }}

                                    </div>

                                </td>


                                {{-- CARNET --}}

                                <td>

                                    <div class="patient-ci">

                                        CI:
                                        {{ $paciente->ci }}

                                    </div>

                                </td>



                                {{-- ACCIONES --}}

                                <td>

                                    <a
                                        href="{{ route(
                                            'reportes.paciente',
                                            ['paciente' => $paciente->id]
                                        ) }}"
                                        class="report-link"
                                    >

                                        Ver reportes →

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

                    Intenta modificar la búsqueda.

                </p>

            </div>

        @endif

    </div>


    {{-- ====================================================== --}}
    {{-- PAGINACIÓN --}}
    {{-- ====================================================== --}}

    @if($pacientes->hasPages())

        <div class="pagination-wrapper">

            {{ $pacientes->links() }}

        </div>

    @endif

</div>

@endsection
