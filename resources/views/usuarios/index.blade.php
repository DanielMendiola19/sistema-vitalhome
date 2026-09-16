@extends('layouts.app')

@section('title', 'Usuarios | VITALHOME')

@section('content')

<div class="container-fluid px-0">

    {{-- =====================================================
         ENCABEZADO
    ====================================================== --}}

    <div class="dashboard-header">

        <div>

            <h1>
                Usuarios
            </h1>

            <p>
                Administración del personal y usuarios del sistema.
            </p>

        </div>

        <div class="usuarios-header-actions">

            <a
                href="{{ route('usuarios.papelera') }}"
                class="btn btn-usuarios-papelera"
            >
                <i class="bi bi-trash3 me-2"></i>
                Usuarios eliminados
            </a>

            <a
                href="{{ route('usuarios.create') }}"
                class="btn btn-vital-primary"
            >
                <i class="bi bi-person-plus me-2"></i>
                Registrar nuevo personal
            </a>

        </div>

    </div>


    {{-- =====================================================
         TARJETA DE USUARIOS
    ====================================================== --}}

    <div class="section-card usuarios-card">

        <div class="usuarios-card-header">

            <div>

                <h3>
                    Personal registrado
                </h3>

                <p>
                    Usuarios con acceso al sistema VITALHOME.
                </p>

            </div>

            <div class="usuarios-total">

                <i class="bi bi-people"></i>

                <span>
                    {{ $usuarios->count() }}
                    {{ $usuarios->count() === 1 ? 'usuario' : 'usuarios' }}
                </span>

            </div>

        </div>


        {{-- =================================================
             TABLA
        ================================================== --}}

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

                        <th class="text-end">
                            Acciones
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse ($usuarios as $usuario)

                        @php
                            $iniciales =
                                strtoupper(
                                    substr($usuario->nombre, 0, 1) .
                                    substr($usuario->apellido, 0, 1)
                                );

                            $rol = match ($usuario->rol) {

    'administrador' => 'Administrador',

    'enfermero' => 'Enfermero',

    'enfermeria' => 'Enfermero',

    'doctor' => 'Médico',

    'medico' => 'Médico',

    'personal' => 'Personal',

    'trabajo_social' => 'Trabajo Social',

    'usuario' => 'Trabajo Social',

    default => ucfirst(
        str_replace('_', ' ', $usuario->rol)
    ),
};
                        @endphp

                        <tr>

                            {{-- =================================
                                 USUARIO
                            ================================== --}}

                            <td>

                                <div class="usuario-info">

                                    <div class="usuario-avatar">
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
                                 ACCIONES
                            ================================== --}}

                            <td>

                                <div class="usuario-actions">

                                    <a
                                        href="{{ route('usuarios.edit', $usuario->id) }}"
                                        class="usuario-action-btn usuario-action-edit"
                                        title="Editar usuario"
                                    >
                                        <i class="bi bi-pencil"></i>
                                    </a>


                                    <form
                                        id="formEliminar{{ $usuario->id }}"
                                        action="{{ route('usuarios.destroy', $usuario->id) }}"
                                        method="POST"
                                        class="d-inline"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="button"
                                            class="usuario-action-btn usuario-action-delete"
                                            title="Eliminar usuario"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalEliminar{{ $usuario->id }}"
                                        >
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>


                                    <div
                                        class="modal fade"
                                        id="modalEliminar{{ $usuario->id }}"
                                        tabindex="-1"
                                        aria-labelledby="modalEliminarLabel{{ $usuario->id }}"
                                        aria-hidden="true"
                                    >
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content modal-vitalhome">

                                                <div class="modal-header border-0">

                                                    <div class="modal-icon modal-icon-danger">
                                                        <i class="bi bi-trash"></i>
                                                    </div>

                                                    <button
                                                        type="button"
                                                        class="btn-close"
                                                        data-bs-dismiss="modal"
                                                        aria-label="Cerrar"
                                                    ></button>

                                                </div>

                                                <div class="modal-body text-center pt-0">

                                                    <h5
                                                        class="modal-title-vitalhome"
                                                        id="modalEliminarLabel{{ $usuario->id }}"
                                                    >
                                                        ¿Eliminar usuario?
                                                    </h5>

                                                    <p class="modal-text-vitalhome">

                                                        Estás a punto de eliminar al usuario

                                                        <strong>
                                                            {{ $usuario->nombre }} {{ $usuario->apellido }}
                                                        </strong>.

                                                    </p>

                                                    <p class="modal-warning-vitalhome">

                                                        <i class="bi bi-info-circle"></i>

                                                        El usuario será eliminado de la lista y ya no podrá
                                                        acceder al sistema.

                                                    </p>

                                                </div>

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
                                                        class="btn-modal-confirmar confirmar-desactivar"
                                                        onclick="document.getElementById('formEliminar{{ $usuario->id }}').submit();"
                                                    >
                                                        <i class="bi bi-trash"></i>
                                                        Sí, eliminar
                                                    </button>

                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <form
                                        id="formEstado{{ $usuario->id }}"
                                        action="{{ route('usuarios.estado', $usuario->id) }}"
                                        method="POST"
                                        class="d-inline"
                                    >
                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="button"
                                            class="btn-estado {{ $usuario->estado === 'activo' ? 'btn-estado-desactivar' : 'btn-estado-activar' }}"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalEstado{{ $usuario->id }}"
                                        >
                                            <i class="bi {{ $usuario->estado === 'activo' ? 'bi-person-slash' : 'bi-person-check' }}"></i>

                                            <span>
                                                {{ $usuario->estado === 'activo' ? 'Desactivar' : 'Activar' }}
                                            </span>
                                        </button>
                                    </form>



                                    <div
                                        class="modal fade"
                                        id="modalEstado{{ $usuario->id }}"
                                        tabindex="-1"
                                        aria-labelledby="modalEstadoLabel{{ $usuario->id }}"
                                        aria-hidden="true"
                                    >
                                        <div class="modal-dialog modal-dialog-centered">

                                            <div class="modal-content modal-vitalhome">

                                                {{-- ENCABEZADO --}}
                                                <div class="modal-header border-0">

                                                    <div class="modal-icon
                                                        {{ $usuario->estado === 'activo'
                                                            ? 'modal-icon-danger'
                                                            : 'modal-icon-success' }}">

                                                        <i class="bi
                                                            {{ $usuario->estado === 'activo'
                                                                ? 'bi-person-slash'
                                                                : 'bi-person-check' }}">
                                                        </i>

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
                                                        id="modalEstadoLabel{{ $usuario->id }}"
                                                    >
                                                        {{ $usuario->estado === 'activo'
                                                            ? '¿Desactivar usuario?'
                                                            : '¿Activar usuario?' }}
                                                    </h5>

                                                    <p class="modal-text-vitalhome">

                                                        Estás a punto de

                                                        <strong>
                                                            {{ $usuario->estado === 'activo'
                                                                ? 'desactivar'
                                                                : 'activar' }}
                                                        </strong>

                                                        el usuario

                                                        <strong>
                                                            {{ $usuario->nombre }}
                                                            {{ $usuario->apellido }}
                                                        </strong>.

                                                    </p>

                                                    <div class="modal-warning-vitalhome">

                                                        <i class="bi bi-info-circle"></i>

                                                        {{ $usuario->estado === 'activo'
                                                            ? 'El usuario ya no podrá iniciar sesión mientras permanezca inactivo.'
                                                            : 'El usuario podrá volver a iniciar sesión en el sistema.' }}

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
                                                        class="btn-modal-confirmar
                                                            {{ $usuario->estado === 'activo'
                                                                ? 'confirmar-desactivar'
                                                                : 'confirmar-activar' }}"
                                                        onclick="document.getElementById('formEstado{{ $usuario->id }}').submit();"
                                                    >

                                                        <i class="bi
                                                            {{ $usuario->estado === 'activo'
                                                                ? 'bi-person-slash'
                                                                : 'bi-person-check' }}">
                                                        </i>

                                                        {{ $usuario->estado === 'activo'
                                                            ? 'Sí, desactivar'
                                                            : 'Sí, activar' }}

                                                    </button>

                                                </div>

                                            </div>

                                        </div>
                                    </div>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="5"
                                class="usuarios-empty"
                            >

                                <div class="usuarios-empty-content">

                                    <div class="usuarios-empty-icon">
                                        <i class="bi bi-people"></i>
                                    </div>

                                    <strong>
                                        No existen usuarios registrados
                                    </strong>

                                    <span>
                                        Registra el primer usuario del sistema.
                                    </span>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>


{{-- =========================================================
     ESTILOS ESPECÍFICOS DEL MÓDULO
========================================================= --}}

<style>

    /* =====================================================
       BOTÓN PRINCIPAL
    ====================================================== */

    .btn-vital-primary {
        display: inline-flex;
        align-items: center;
        justify-content: center;

        min-height: 42px;

        padding: 10px 16px;

        border: 1px solid var(--vh-green);
        border-radius: 9px;

        background: var(--vh-green);
        color: #ffffff;

        font-size: 13px;
        font-weight: 600;

        transition:
            background-color 0.2s ease,
            border-color 0.2s ease,
            transform 0.2s ease,
            box-shadow 0.2s ease;
    }

    .btn-vital-primary:hover {
        background: #43875a;
        border-color: #43875a;
        color: #ffffff;

        transform: translateY(-1px);

        box-shadow: 0 4px 12px rgba(79, 157, 105, 0.18);
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

    .usuarios-total {
        display: inline-flex;

        align-items: center;

        gap: 7px;

        padding: 7px 11px;

        border-radius: 20px;

        background: var(--vh-green-light);
        color: var(--vh-green);

        font-size: 12px;
        font-weight: 600;

        white-space: nowrap;
    }

    .usuarios-total i {
        font-size: 14px;
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

        min-width: 760px;

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

        background: var(--vh-green-light);
        color: var(--vh-green);

        font-size: 12px;
        font-weight: 700;
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
       ACCIONES
    ====================================================== */

    .usuario-actions {
        display: flex;

        align-items: center;
        justify-content: flex-end;

        gap: 6px;
    }

    .usuario-action-btn {
        width: 34px;
        height: 34px;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        padding: 0;

        border-radius: 8px;

        font-size: 14px;

        transition:
            background-color 0.2s ease,
            color 0.2s ease,
            border-color 0.2s ease,
            transform 0.2s ease;
    }

    .usuario-action-edit {
        border: 1px solid var(--vh-border);

        background: #ffffff;
        color: var(--vh-blue);
    }

    .usuario-action-edit:hover {
        background: #eef3f8;

        border-color: #d7e0e8;

        color: var(--vh-blue);

        transform: translateY(-1px);
    }

    .usuario-action-delete {
        border: 1px solid #f0d8d8;

        background: #ffffff;
        color: var(--vh-danger);

        cursor: pointer;
    }

    .usuario-action-delete:hover {
        background: var(--vh-danger-light);

        border-color: #e9c4c4;

        color: var(--vh-danger);

        transform: translateY(-1px);
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

        background: var(--vh-green-light);
        color: var(--vh-green);

        font-size: 20px;
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
       RESPONSIVE
    ====================================================== */

    @media (max-width: 767.98px) {

        .dashboard-header {
            gap: 14px;
        }

        .btn-vital-primary {
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

    }

    .usuarios-header-actions {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .btn-usuarios-papelera {
        display: inline-flex;
        align-items: center;
        justify-content: center;

        min-height: 42px;

        padding: 10px 16px;

        border: 1px solid #f0d8d8;
        border-radius: 9px;

        background: #ffffff;
        color: var(--vh-danger);

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

    .btn-usuarios-papelera:hover {
        background: var(--vh-danger-light);
        border-color: #e9c4c4;
        color: var(--vh-danger);

        transform: translateY(-1px);

        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.12);
    }


    @media (max-width: 767.98px) {

        .usuarios-header-actions {
            width: 100%;
            flex-direction: column;
        }

        .usuarios-header-actions a {
            width: 100%;
        }

    }

    /* =========================================
        BOTÓN ACTIVAR / DESACTIVAR
        ========================================= */

        .btn-estado {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;

            min-width: 120px;
            padding: 7px 12px;

            border: none;
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

        /* Hover general */

        .btn-estado:hover {
            transform: translateY(-1px);
        }

        .btn-estado:active {
            transform: translateY(0);
        }

        /* Desactivar */

        .btn-estado-desactivar {
            background-color: #fff1f2;
            color: #dc3545;
            border: 1px solid #fecdd3;
        }

        .btn-estado-desactivar:hover {
            background-color: #ffe4e6;
            color: #b91c1c;
            box-shadow: 0 4px 10px rgba(220, 53, 69, 0.15);
        }

        /* Activar */

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


        /* =========================================
   MODAL VITALHOME
========================================= */

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

.modal-icon-danger {
    background: #fff1f2;
    color: #dc3545;
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

.confirmar-desactivar {
    background: #dc3545;
    color: #ffffff;
}

.confirmar-desactivar:hover {
    background: #bb2d3b;
    box-shadow: 0 4px 10px rgba(220, 53, 69, 0.20);
}

.confirmar-activar {
    background: var(--vh-green);
    color: #ffffff;
}

.confirmar-activar:hover {
    background: #43875a;
    box-shadow: 0 4px 10px rgba(79, 157, 105, 0.20);
}

</style>

@endsection
