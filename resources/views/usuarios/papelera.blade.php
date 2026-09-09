@extends('layouts.app')

@section('title', 'Papelera de usuarios | VITALHOME')

@section('content')

<div class="container-fluid px-0">

{{-- =====================================================
     ENCABEZADO
====================================================== --}}

<div class="dashboard-header">

    <div>

        <h1>
            Papelera de usuarios
        </h1>

        <p>
            Usuarios eliminados del sistema.
        </p>

    </div>


    <div>

        <a
            href="{{ route('usuarios.index') }}"
            class="btn btn-vital-secondary"
        >
            <i class="bi bi-arrow-left me-2"></i>
            Volver a usuarios
        </a>

    </div>

</div>


{{-- =====================================================
     TARJETA DE USUARIOS ELIMINADOS
====================================================== --}}

<div class="section-card usuarios-card">

    <div class="usuarios-card-header">

        <div>

            <h3>
                Usuarios eliminados
            </h3>

            <p>
                Desde aquí puedes restaurar usuarios eliminados.
            </p>

        </div>


        <div class="usuarios-total usuarios-total-papelera">

            <i class="bi bi-trash3"></i>

            <span>
                {{ $usuarios->count() }}
                {{ $usuarios->count() === 1 ? 'usuario' : 'usuarios' }}
            </span>

        </div>

    </div>


    {{-- =================================================
         MENSAJE DE ÉXITO
    ================================================== --}}

    @if (session('success'))

        <div class="papelera-success">

            <i class="bi bi-check-circle"></i>

            <span>
                {{ session('success') }}
            </span>

        </div>

    @endif


    {{-- =================================================
         TABLA / ESTADO VACÍO
    ================================================== --}}

    @if ($usuarios->isEmpty())

        <div class="usuarios-empty">

            <div class="usuarios-empty-content">

                <div class="usuarios-empty-icon usuarios-empty-icon-trash">

                    <i class="bi bi-trash3"></i>

                </div>


                <strong>
                    La papelera está vacía
                </strong>


                <span>
                    No existen usuarios eliminados actualmente.
                </span>

            </div>

        </div>

    @else

        <div class="table-responsive usuarios-table-wrapper">

            <table class="usuarios-table">

                <thead>

                    <tr>

                        <th>
                            Usuario
                        </th>

                        <th>
                            Correo electrónico
                        </th>

                        <th>
                            Rol
                        </th>

                        <th>
                            Estado
                        </th>

                        <th>
                            Eliminado
                        </th>

                        <th class="text-end">
                            Acción
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @foreach ($usuarios as $usuario)

                        @php

                            $iniciales =
                                strtoupper(
                                    substr($usuario->nombre, 0, 1) .
                                    substr($usuario->apellido, 0, 1)
                                );

                            $rol = match ($usuario->rol) {

                                'administrador' => 'Administrador',

                                'enfermero' => 'Enfermero',

                                'enfermeria' => 'Enfermería',

                                'medico' => 'Médico',

                                'personal' => 'Personal',

                                'usuario' => 'Usuario',

                                default => ucfirst($usuario->rol),

                            };

                        @endphp


                        <tr>

                            {{-- =================================
                                 USUARIO
                            ================================== --}}

                            <td>

                                <div class="usuario-info">

                                    <div class="usuario-avatar usuario-avatar-papelera">

                                        {{ $iniciales }}

                                    </div>


                                    <div class="usuario-datos">

                                        <strong>

                                            {{ $usuario->nombre }}
                                            {{ $usuario->apellido }}

                                        </strong>


                                        <small>

                                            ID #{{ $usuario->id }}

                                        </small>

                                    </div>

                                </div>

                            </td>


                            {{-- =================================
                                 CORREO
                            ================================== --}}

                            <td>

                                <span class="usuario-email">

                                    {{ $usuario->email }}

                                </span>

                            </td>


                            {{-- =================================
                                 ROL
                            ================================== --}}

                            <td>

                                <span class="usuario-role">

                                    {{ $rol }}

                                </span>

                            </td>


                            {{-- =================================
                                 ESTADO
                            ================================== --}}

                            <td>

                                @if ($usuario->estado === 'activo')

                                    <span class="status available">

                                        <span class="status-dot"></span>

                                        Activo

                                    </span>

                                @else

                                    <span class="status danger">

                                        <span class="status-dot"></span>

                                        Inactivo

                                    </span>

                                @endif

                            </td>


                            {{-- =================================
                                 FECHA DE ELIMINACIÓN
                            ================================== --}}

                            <td>

                                <span class="usuario-fecha-eliminacion">

                                    <i class="bi bi-calendar3"></i>

                                    {{ $usuario->deleted_at->format('d/m/Y H:i') }}

                                </span>

                            </td>


                            {{-- =================================
                                 ACCIÓN
                            ================================== --}}

                            <td>

                                <div class="usuario-actions">

                                    <form
                                        id="formRestaurar{{ $usuario->id }}"
                                        action="{{ route('usuarios.restaurar', $usuario->id) }}"
                                        method="POST"
                                        class="d-inline"
                                    >

                                        @csrf

                                        @method('PATCH')


                                        <button
                                            type="button"
                                            class="btn-estado btn-estado-activar"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalRestaurar{{ $usuario->id }}"
                                            title="Restaurar usuario"
                                        >

                                            <i class="bi bi-arrow-counterclockwise"></i>

                                            <span>
                                                Restaurar
                                            </span>

                                        </button>

                                    </form>


                                    {{-- =================================
                                         MODAL RESTAURAR
                                    ================================== --}}

                                    <div
                                        class="modal fade"
                                        id="modalRestaurar{{ $usuario->id }}"
                                        tabindex="-1"
                                        aria-labelledby="modalRestaurarLabel{{ $usuario->id }}"
                                        aria-hidden="true"
                                    >

                                        <div class="modal-dialog modal-dialog-centered">

                                            <div class="modal-content modal-vitalhome">


                                                {{-- ENCABEZADO --}}

                                                <div class="modal-header border-0">

                                                    <div class="modal-icon modal-icon-success">

                                                        <i class="bi bi-arrow-counterclockwise"></i>

                                                    </div>


                                                    <button
                                                        type="button"
                                                        class="btn-close"
                                                        data-bs-dismiss="modal"
                                                        aria-label="Cerrar"
                                                    ></button>

                                                </div>


                                                {{-- CONTENIDO --}}

                                                <div class="modal-body text-center pt-0">

                                                    <h5
                                                        class="modal-title-vitalhome"
                                                        id="modalRestaurarLabel{{ $usuario->id }}"
                                                    >

                                                        ¿Restaurar usuario?

                                                    </h5>


                                                    <p class="modal-text-vitalhome">

                                                        El usuario

                                                        <strong>
                                                            {{ $usuario->nombre }}
                                                            {{ $usuario->apellido }}
                                                        </strong>

                                                        volverá a aparecer en la lista
                                                        de usuarios.

                                                    </p>


                                                    <div class="modal-warning-vitalhome modal-success-vitalhome">

                                                        <i class="bi bi-info-circle"></i>

                                                        El usuario volverá a estar disponible
                                                        en el sistema y podrá acceder según
                                                        su estado actual.

                                                    </div>

                                                </div>


                                                {{-- BOTONES --}}

                                                <div class="modal-footer border-0 justify-content-center">

                                                    <button
                                                        type="button"
                                                        class="btn-modal-cancelar"
                                                        data-bs-dismiss="modal"
                                                    >

                                                        Cancelar

                                                    </button>


                                                    <button
                                                        type="button"
                                                        class="btn-modal-confirmar confirmar-activar"
                                                        onclick="document.getElementById('formRestaurar{{ $usuario->id }}').submit();"
                                                    >

                                                        <i class="bi bi-arrow-counterclockwise"></i>

                                                        Sí, restaurar

                                                    </button>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    @endif

</div>


</div>

{{-- =========================================================
ESTILOS ESPECÍFICOS DE LA PAPELERA
========================================================= --}}

<style>


    /* =====================================================
       BOTÓN VOLVER
    ====================================================== */

    .btn-vital-secondary {

        display: inline-flex;

        align-items: center;

        justify-content: center;

        min-height: 42px;

        padding: 10px 16px;

        border: 1px solid var(--vh-border);

        border-radius: 9px;

        background: #ffffff;

        color: var(--vh-blue);

        font-size: 13px;

        font-weight: 600;

        text-decoration: none;

        transition:
            background-color 0.2s ease,
            border-color 0.2s ease,
            color 0.2s ease,
            transform 0.2s ease,
            box-shadow 0.2s ease;

    }


    .btn-vital-secondary:hover {

        background: #eef3f8;

        border-color: #d7e0e8;

        color: var(--vh-blue);

        transform: translateY(-1px);

        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);

    }


    /* =====================================================
       TARJETA
    ====================================================== */

    .usuarios-card {

        overflow: hidden;

    }


    /* =====================================================
       ENCABEZADO DE LA TARJETA
    ====================================================== */

    .usuarios-card-header {

        display: flex;

        align-items: center;

        justify-content: space-between;

        gap: 20px;

        padding: 20px 22px;

        border-bottom: 1px solid var(--vh-border);

    }


    .usuarios-card-header h3 {

        margin: 0;

        color: var(--vh-blue);

        font-size: 17px;

        font-weight: 650;

    }


    .usuarios-card-header p {

        margin: 4px 0 0;

        color: var(--vh-text-muted);

        font-size: 12px;

    }


    /* =====================================================
       CONTADOR PAPELERA
    ====================================================== */

    .usuarios-total {

        display: inline-flex;

        align-items: center;

        gap: 7px;

        padding: 7px 11px;

        border-radius: 20px;

        font-size: 12px;

        font-weight: 600;

        white-space: nowrap;

    }


    .usuarios-total-papelera {

        background: #fff1f2;

        color: var(--vh-danger);

    }


    .usuarios-total i {

        font-size: 14px;

    }


    /* =====================================================
       MENSAJE DE ÉXITO
    ====================================================== */

    .papelera-success {

        display: flex;

        align-items: center;

        gap: 9px;

        margin: 18px 22px 0;

        padding: 12px 15px;

        border: 1px solid #d5eadb;

        border-radius: 9px;

        background: #edf7f0;

        color: #198754;

        font-size: 13px;

        font-weight: 500;

    }


    .papelera-success i {

        font-size: 16px;

    }


    /* =====================================================
       TABLA
    ====================================================== */

    .usuarios-table-wrapper {

        width: 100%;

        overflow-x: auto;

    }


    .usuarios-table {

        width: 100%;

        min-width: 900px;

        margin: 0;

        border-collapse: collapse;

    }


    .usuarios-table th {

        padding: 12px 22px;

        background: #fbfcfd;

        color: var(--vh-text-muted);

        border-bottom: 1px solid var(--vh-border);

        font-size: 11px;

        font-weight: 600;

        text-transform: uppercase;

        letter-spacing: 0.3px;

        white-space: nowrap;

    }


    .usuarios-table td {

        padding: 14px 22px;

        color: var(--vh-text);

        border-bottom: 1px solid #f0f2f4;

        font-size: 13px;

        vertical-align: middle;

    }


    .usuarios-table tbody tr {

        transition: background-color 0.15s ease;

    }


    .usuarios-table tbody tr:hover {

        background: #fafcfd;

    }


    .usuarios-table tbody tr:last-child td {

        border-bottom: none;

    }


    /* =====================================================
       INFORMACIÓN DEL USUARIO
    ====================================================== */

    .usuario-info {

        display: flex;

        align-items: center;

        gap: 11px;

        min-width: 220px;

    }


    .usuario-avatar {

        width: 40px;

        height: 40px;

        flex-shrink: 0;

        display: flex;

        align-items: center;

        justify-content: center;

        border-radius: 50%;

        font-size: 12px;

        font-weight: 700;

    }


    .usuario-avatar-papelera {

        background: #f1f3f5;

        color: #6b7785;

    }


    .usuario-datos {

        display: flex;

        flex-direction: column;

        min-width: 0;

    }


    .usuario-datos strong {

        color: var(--vh-text);

        font-size: 13px;

        font-weight: 600;

        line-height: 1.3;

    }


    .usuario-datos small {

        margin-top: 2px;

        color: var(--vh-text-muted);

        font-size: 10px;

    }


    /* =====================================================
       CORREO
    ====================================================== */

    .usuario-email {

        color: var(--vh-text-secondary);

        font-size: 13px;

        white-space: nowrap;

    }


    /* =====================================================
       ROL
    ====================================================== */

    .usuario-role {

        display: inline-flex;

        align-items: center;

        padding: 5px 9px;

        border-radius: 7px;

        background: #eef3f8;

        color: var(--vh-blue);

        font-size: 11px;

        font-weight: 600;

        white-space: nowrap;

    }


    /* =====================================================
       ESTADO
    ====================================================== */

    .usuarios-table .status {

        display: inline-flex;

        align-items: center;

        gap: 5px;

    }


    .status-dot {

        width: 6px;

        height: 6px;

        border-radius: 50%;

        background: currentColor;

    }


    /* =====================================================
       FECHA DE ELIMINACIÓN
    ====================================================== */

    .usuario-fecha-eliminacion {

        display: inline-flex;

        align-items: center;

        gap: 6px;

        color: var(--vh-text-muted);

        font-size: 12px;

        white-space: nowrap;

    }


    .usuario-fecha-eliminacion i {

        font-size: 13px;

    }


    /* =====================================================
       ACCIONES
    ====================================================== */

    .usuario-actions {

        display: flex;

        align-items: center;

        justify-content: flex-end;

        gap: 6px;

    }


    /* =====================================================
       BOTÓN RESTAURAR
    ====================================================== */

    .btn-estado {

        display: inline-flex;

        align-items: center;

        justify-content: center;

        gap: 7px;

        min-width: 120px;

        padding: 7px 12px;

        border-radius: 8px;

        font-size: 13px;

        font-weight: 600;

        cursor: pointer;

        transition:
            transform 0.2s ease,
            box-shadow 0.2s ease,
            background-color 0.2s ease;

    }


    .btn-estado i {

        font-size: 15px;

    }


    .btn-estado:hover {

        transform: translateY(-1px);

    }


    .btn-estado:active {

        transform: translateY(0);

    }


    .btn-estado-activar {

        background-color: #ecfdf5;

        color: #198754;

        border: 1px solid #a7f3d0;

    }


    .btn-estado-activar:hover {

        background-color: #d1fae5;

        color: #157347;

        box-shadow: 0 4px 10px rgba(25, 135, 84, 0.15);

    }


    /* =====================================================
       ESTADO VACÍO
    ====================================================== */

    .usuarios-empty {

        padding: 50px 20px !important;

        text-align: center;

    }


    .usuarios-empty-content {

        display: flex;

        align-items: center;

        justify-content: center;

        flex-direction: column;

        gap: 5px;

    }


    .usuarios-empty-icon {

        width: 48px;

        height: 48px;

        display: flex;

        align-items: center;

        justify-content: center;

        margin-bottom: 7px;

        border-radius: 50%;

        font-size: 20px;

    }


    .usuarios-empty-icon-trash {

        background: #f1f3f5;

        color: #6b7785;

    }


    .usuarios-empty-content strong {

        color: var(--vh-text);

        font-size: 14px;

    }


    .usuarios-empty-content span {

        color: var(--vh-text-muted);

        font-size: 12px;

    }


    /* =====================================================
       MODAL VITALHOME
    ====================================================== */

    .modal-vitalhome {

        border: none;

        border-radius: 16px;

        overflow: hidden;

        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);

    }


    .modal-vitalhome .modal-header {

        padding: 20px 20px 10px;

    }


    .modal-icon {

        width: 52px;

        height: 52px;

        display: flex;

        align-items: center;

        justify-content: center;

        border-radius: 50%;

        font-size: 22px;

    }


    .modal-icon-success {

        background: #ecfdf5;

        color: #198754;

    }


    .modal-title-vitalhome {

        margin-bottom: 10px;

        color: var(--vh-blue);

        font-size: 20px;

        font-weight: 700;

    }


    .modal-text-vitalhome {

        margin-bottom: 18px;

        color: var(--vh-text-muted);

        font-size: 14px;

        line-height: 1.6;

    }


    .modal-warning-vitalhome {

        padding: 12px 15px;

        border-radius: 10px;

        background: #f8f9fa;

        color: var(--vh-text-muted);

        font-size: 12px;

        line-height: 1.5;

    }


    .modal-warning-vitalhome i {

        margin-right: 5px;

    }


    .modal-success-vitalhome {

        background: #ecfdf5;

        color: #157347;

    }


    /* =====================================================
       BOTONES DEL MODAL
    ====================================================== */

    .btn-modal-cancelar,
    .btn-modal-confirmar {

        display: inline-flex;

        align-items: center;

        justify-content: center;

        gap: 6px;

        padding: 9px 18px;

        border: none;

        border-radius: 8px;

        font-size: 13px;

        font-weight: 600;

        cursor: pointer;

        transition:
            background-color 0.2s ease,
            transform 0.2s ease,
            box-shadow 0.2s ease;

    }


    .btn-modal-cancelar {

        background: #f1f3f5;

        color: #495057;

    }


    .btn-modal-cancelar:hover {

        background: #e9ecef;

    }


    .btn-modal-confirmar:hover {

        transform: translateY(-1px);

    }


    .confirmar-activar {

        background: var(--vh-green);

        color: #ffffff;

    }


    .confirmar-activar:hover {

        background: #43875a;

        box-shadow: 0 4px 10px rgba(79, 157, 105, 0.20);

    }


    /* =====================================================
       RESPONSIVE
    ====================================================== */

    @media (max-width: 767.98px) {

        .dashboard-header {

            gap: 14px;

        }


        .btn-vital-secondary {

            width: 100%;

        }


        .dashboard-header > div:last-child {

            width: 100%;

        }


        .usuarios-card-header {

            align-items: flex-start;

            flex-direction: column;

            padding: 17px;

        }


        .usuarios-table th {

            padding: 11px 17px;

        }


        .usuarios-table td {

            padding: 13px 17px;

        }


        .papelera-success {

            margin-left: 17px;

            margin-right: 17px;

        }

    }

</style>

@endsection
