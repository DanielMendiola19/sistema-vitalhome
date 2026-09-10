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

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
    }

    .status-active {
        background: #dcfce7;
        color: #15803d;
    }

    .status-inactive {
        background: #f1f5f9;
        color: #64748b;
    }

</style>


<div class="patients-page">

    {{-- HEADER --}}

    <div class="patients-header">

        <div>

            <h1 class="patients-title">
                Tratamientos / Kardex
            </h1>

            <p class="patients-subtitle">
                Consulta y gestión de tratamientos por paciente
            </p>

        </div>

    </div>


    {{-- BÚSQUEDA --}}

    <form
        method="GET"
        action="{{ route('tratamientos_kardex.lista') }}"
        class="search-form"
    >

        <div class="search-wrapper">

            <i class="bi bi-search"></i>

            <input
                type="text"
                name="buscar"
                value="{{ $buscar ?? '' }}"
                class="search-input"
                placeholder="Buscar paciente por nombre o carnet..."
            >

        </div>

    </form>


    {{-- TABLA --}}

    <div class="patients-card">

        @if($pacientes->count())

            <div class="patients-table-wrapper">

                <table class="patients-table">

                    <thead>

                        <tr>

                            <th>
                                PACIENTE
                            </th>

                            <th>
                                CARNET
                            </th>

                            <th>
                                TRATAMIENTOS
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

                        @foreach($pacientes as $paciente)

                            @php

                                $tratamientosActivos =
                                    $paciente->tratamientos
                                        ->where('estado', 'activo')
                                        ->count();

                                $totalTratamientos =
                                    $paciente->tratamientos->count();

                            @endphp


                            <tr>

                                {{-- PACIENTE --}}

                                <td>

                                    <a
                                        href="{{ route(
                                            'tratamientos_kardex.index',
                                            ['paciente' => $paciente->id]
                                        ) }}"
                                        class="patient-name"
                                    >

                                        {{ $paciente->nombre }}
                                        {{ $paciente->apellido }}

                                    </a>

                                </td>


                                {{-- CI --}}

                                <td>

                                    <div class="patient-ci">

                                        CI:
                                        {{ $paciente->ci }}

                                    </div>

                                </td>


                                {{-- TRATAMIENTOS --}}

                                <td>

                                    <span class="med-count">

                                        {{ $totalTratamientos }}

                                    </span>

                                </td>


                                {{-- ESTADO --}}

                                <td>

                                    @if($tratamientosActivos > 0)

                                        <span class="status-badge status-active">

                                            Activo

                                        </span>

                                    @else

                                        <span class="status-badge status-inactive">

                                            Sin tratamiento activo

                                        </span>

                                    @endif

                                </td>


                                {{-- ACCIONES --}}

                                <td>

                                    <a
                                        href="{{ route(
                                            'tratamientos_kardex.index',
                                            ['paciente' => $paciente->id]
                                        ) }}"
                                        class="profile-link"
                                    >

                                        Ver Kardex →

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

</div>

@endsection
