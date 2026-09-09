@extends('layouts.app')

@section('title', 'Registrar personal | VITALHOME')

@section('content')

<div class="container-fluid px-0">

    {{-- =====================================================
         ENCABEZADO
    ====================================================== --}}

    <div class="dashboard-header">

        <div>

            <h1>
                Registrar nuevo personal
            </h1>

            <p>
                Crea una nueva cuenta de acceso al sistema VITALHOME.
            </p>

        </div>

    </div>


    {{-- =====================================================
         FORMULARIO
    ====================================================== --}}

    <div class="section-card usuario-form-card">

        <div class="usuario-form-header">

            <div class="usuario-form-icon">
                <i class="bi bi-person-plus"></i>
            </div>

            <div>

                <h3>
                    Datos del usuario
                </h3>

                <p>
                    Completa la información del nuevo personal.
                </p>

            </div>

        </div>


        <div class="usuario-form-body">

            <form
                method="POST"
                action="{{ route('usuarios.store') }}"
            >

                @csrf


                {{-- =========================================
                     NOMBRE
                ========================================== --}}

                <div class="row g-3">

                    <div class="col-md-6">

                        <label
                            for="nombre"
                            class="form-label usuario-form-label"
                        >
                            Nombre
                        </label>

                        <input
                            type="text"
                            id="nombre"
                            name="nombre"
                            value="{{ old('nombre') }}"
                            class="form-control usuario-form-control @error('nombre') is-invalid @enderror"
                            placeholder="Ej. Juan"
                            maxlength="100"
                            required
                            autofocus
                        >

                        @error('nombre')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- =====================================
                         APELLIDO
                    ====================================== --}}

                    <div class="col-md-6">

                        <label
                            for="apellido"
                            class="form-label usuario-form-label"
                        >
                            Apellido
                        </label>

                        <input
                            type="text"
                            id="apellido"
                            name="apellido"
                            value="{{ old('apellido') }}"
                            class="form-control usuario-form-control @error('apellido') is-invalid @enderror"
                            placeholder="Ej. Pérez"
                            maxlength="100"
                            required
                        >

                        @error('apellido')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- =====================================
                         ROL
                    ====================================== --}}

                    <div class="col-md-6">

                        <label
                            for="rol"
                            class="form-label usuario-form-label"
                        >
                            Rol
                        </label>

                        <select
                            id="rol"
                            name="rol"
                            class="form-select usuario-form-control @error('rol') is-invalid @enderror"
                            required
                        >

                            <option value="" disabled {{ old('rol') ? '' : 'selected' }}>
                                Selecciona un rol
                            </option>

                            <option
                                value="administrador"
                                {{ old('rol') === 'administrador' ? 'selected' : '' }}
                            >
                                Administrador
                            </option>

                            <option
                                value="enfermero"
                                {{ old('rol') === 'enfermero' ? 'selected' : '' }}
                            >
                                Enfermero
                            </option>

                            <option
                                value="medico"
                                {{ old('rol') === 'medico' ? 'selected' : '' }}
                            >
                                Médico
                            </option>

                            <option
                                value="personal"
                                {{ old('rol') === 'personal' ? 'selected' : '' }}
                            >
                                Personal
                            </option>

                            <option
                                value="usuario"
                                {{ old('rol') === 'usuario' ? 'selected' : '' }}
                            >
                                Usuario
                            </option>

                        </select>

                        @error('rol')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- =====================================
                         CORREO
                    ====================================== --}}

                    <div class="col-md-6">

                        <label
                            for="email"
                            class="form-label usuario-form-label"
                        >
                            Correo electrónico
                        </label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            class="form-control usuario-form-control @error('email') is-invalid @enderror"
                            placeholder="Ej. usuario@vitalhome.com"
                            maxlength="150"
                            required
                        >

                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>


                {{-- =========================================
                     INFORMACIÓN SOBRE CONTRASEÑA
                ========================================== --}}

                <div class="usuario-password-info">

                    <div class="usuario-password-info-icon">
                        <i class="bi bi-shield-lock"></i>
                    </div>

                    <div>

                        <strong>
                            Contraseña temporal
                        </strong>

                        <p>
                            El sistema generará automáticamente una contraseña
                            temporal y la enviará al correo electrónico registrado.
                        </p>

                    </div>

                </div>


                {{-- =========================================
                     BOTONES
                ========================================== --}}

                <div class="usuario-form-actions">

                    <a
                        href="{{ route('usuarios.index') }}"
                        class="btn btn-vital-secondary"
                    >
                        <i class="bi bi-arrow-left me-2"></i>
                        Cancelar
                    </a>

                    <button
                        type="submit"
                        class="btn btn-vital-primary"
                    >
                        <i class="bi bi-person-plus me-2"></i>
                        Registrar personal
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- =========================================================
     ESTILOS
========================================================= --}}

<style>

    .usuario-form-card {
        max-width: 900px;
        overflow: hidden;
    }


    /* =====================================================
       ENCABEZADO
    ====================================================== */

    .usuario-form-header {
        display: flex;

        align-items: center;

        gap: 13px;

        padding: 20px 22px;

        border-bottom: 1px solid var(--vh-border);
    }

    .usuario-form-icon {
        width: 42px;
        height: 42px;

        flex-shrink: 0;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 11px;

        background: var(--vh-green-light);
        color: var(--vh-green);

        font-size: 18px;
    }

    .usuario-form-header h3 {
        margin: 0;

        color: var(--vh-blue);

        font-size: 17px;
        font-weight: 650;
    }

    .usuario-form-header p {
        margin: 3px 0 0;

        color: var(--vh-text-muted);

        font-size: 12px;
    }


    /* =====================================================
       CUERPO
    ====================================================== */

    .usuario-form-body {
        padding: 24px 22px;
    }


    /* =====================================================
       CAMPOS
    ====================================================== */

    .usuario-form-label {
        margin-bottom: 7px;

        color: var(--vh-text);

        font-size: 13px;
        font-weight: 600;
    }

    .usuario-form-control {
        min-height: 43px;

        border: 1px solid #dce2e7;
        border-radius: 9px;

        color: var(--vh-text);

        font-size: 13px;

        box-shadow: none;
    }

    .usuario-form-control:focus {
        border-color: var(--vh-green);

        box-shadow: 0 0 0 3px rgba(79, 157, 105, 0.10);
    }

    .usuario-form-control::placeholder {
        color: #adb5bd;
    }

    select.usuario-form-control {
        cursor: pointer;
    }


    /* =====================================================
       INFORMACIÓN CONTRASEÑA
    ====================================================== */

    .usuario-password-info {
        display: flex;

        align-items: flex-start;

        gap: 12px;

        margin-top: 24px;
        padding: 14px 15px;

        border: 1px solid #dcebe0;
        border-radius: 10px;

        background: var(--vh-green-light);
    }

    .usuario-password-info-icon {
        width: 34px;
        height: 34px;

        flex-shrink: 0;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 8px;

        background: #ffffff;
        color: var(--vh-green);

        font-size: 15px;
    }

    .usuario-password-info strong {
        display: block;

        color: var(--vh-blue);

        font-size: 12px;
        font-weight: 650;
    }

    .usuario-password-info p {
        margin: 3px 0 0;

        color: var(--vh-text-secondary);

        font-size: 11px;

        line-height: 1.5;
    }


    /* =====================================================
       BOTONES
    ====================================================== */

    .usuario-form-actions {
        display: flex;

        align-items: center;
        justify-content: flex-end;

        gap: 9px;

        margin-top: 26px;

        padding-top: 20px;

        border-top: 1px solid var(--vh-border);
    }

    .btn-vital-secondary {
        display: inline-flex;
        align-items: center;
        justify-content: center;

        min-height: 42px;

        padding: 10px 16px;

        border: 1px solid var(--vh-border);
        border-radius: 9px;

        background: #ffffff;
        color: var(--vh-text-secondary);

        font-size: 13px;
        font-weight: 600;

        transition: 0.2s ease;
    }

    .btn-vital-secondary:hover {
        background: #f8fafb;

        border-color: #d8dee4;

        color: var(--vh-blue);
    }


    /* =====================================================
       RESPONSIVE
    ====================================================== */

    @media (max-width: 767.98px) {

        .usuario-form-header {
            padding: 17px;
        }

        .usuario-form-body {
            padding: 20px 17px;
        }

        .usuario-form-actions {
            flex-direction: column-reverse;

            align-items: stretch;
        }

        .usuario-form-actions .btn {
            width: 100%;
        }

    }

</style>

@endsection
