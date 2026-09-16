@extends('layouts.app')

@section('title', 'Editar usuario | VITALHOME')

@section('content')

<div class="container-fluid">

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h1 class="h3 mb-1">
            Editar usuario
        </h1>

        <p class="text-muted mb-0">
            Modifica los datos del usuario seleccionado.
        </p>

    </div>


    <a
        href="{{ route('usuarios.index') }}"
        class="btn btn-secondary"
    >
        <i class="bi bi-arrow-left"></i>
        Volver
    </a>

</div>


{{-- Mensajes de validación --}}

@if ($errors->any())

    <div class="alert alert-danger">

        <strong>
            Se encontraron algunos errores:
        </strong>

        <ul class="mb-0 mt-2">

            @foreach ($errors->all() as $error)

                <li>
                    {{ $error }}
                </li>

            @endforeach

        </ul>

    </div>

@endif


<div class="card shadow-sm border-0">

    <div class="card-header bg-white py-3">

        <h5 class="mb-0">

            <i class="bi bi-person-gear"></i>

            Información del usuario

        </h5>

    </div>


    <div class="card-body">

        <form
            action="{{ route('usuarios.update', $usuario->id) }}"
            method="POST"
        >

            @csrf
            @method('PUT')


            <div class="row g-3">


                {{-- Nombre --}}

                <div class="col-md-6">

                    <label
                        for="nombre"
                        class="form-label"
                    >
                        Nombre
                    </label>

                    <input
                        type="text"
                        name="nombre"
                        id="nombre"
                        class="form-control @error('nombre') is-invalid @enderror"
                        value="{{ old('nombre', $usuario->nombre) }}"
                        required
                    >

                    @error('nombre')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Apellido --}}

                <div class="col-md-6">

                    <label
                        for="apellido"
                        class="form-label"
                    >
                        Apellido
                    </label>

                    <input
                        type="text"
                        name="apellido"
                        id="apellido"
                        class="form-control @error('apellido') is-invalid @enderror"
                        value="{{ old('apellido', $usuario->apellido) }}"
                        required
                    >

                    @error('apellido')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Correo --}}

                <div class="col-md-6">

                    <label
                        for="email"
                        class="form-label"
                    >
                        Correo electrónico
                    </label>

                    <input
                        type="email"
                        name="email"
                        id="email"
                        class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email', $usuario->email) }}"
                        required
                    >

                    @error('email')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Rol --}}

                <div class="col-md-6">

                    <label
                        for="rol"
                        class="form-label"
                    >
                        Rol
                    </label>

                    @php
                        $rolActual = old('rol', $usuario->rol);
                    @endphp

                    <select
                        name="rol"
                        id="rol"
                        class="form-select @error('rol') is-invalid @enderror"
                        required
                    >

                        <option value="">
                            Seleccionar rol
                        </option>


                        <option
                            value="administrador"
                            {{ $rolActual === 'administrador' ? 'selected' : '' }}
                        >
                            Administrador
                        </option>


                        <option
                            value="enfermero"
                            {{ in_array($rolActual, ['enfermero', 'enfermeria']) ? 'selected' : '' }}
                        >
                            Enfermero
                        </option>


                        <option
                            value="doctor"
                            {{ in_array($rolActual, ['doctor', 'medico']) ? 'selected' : '' }}
                        >
                            Médico
                        </option>


                        <option
                            value="personal"
                            {{ $rolActual === 'personal' ? 'selected' : '' }}
                        >
                            Personal
                        </option>


                        <option
                            value="trabajo_social"
                            {{ in_array($rolActual, ['trabajo_social', 'usuario']) ? 'selected' : '' }}
                        >
                            Trabajo Social
                        </option>

                    </select>


                    @error('rol')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- Estado --}}

                <div class="col-md-6">

                    <label
                        for="estado"
                        class="form-label"
                    >
                        Estado
                    </label>

                    <select
                        name="estado"
                        id="estado"
                        class="form-select @error('estado') is-invalid @enderror"
                        required
                    >

                        <option
                            value="activo"
                            {{ old('estado', $usuario->estado) === 'activo' ? 'selected' : '' }}
                        >
                            Activo
                        </option>


                        <option
                            value="inactivo"
                            {{ old('estado', $usuario->estado) === 'inactivo' ? 'selected' : '' }}
                        >
                            Inactivo
                        </option>

                    </select>


                    @error('estado')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>

            </div>


            {{-- Información sobre contraseña --}}

            <div class="alert alert-info mt-4">

                <i class="bi bi-info-circle"></i>

                <strong>
                    Contraseña:
                </strong>

                la contraseña no se modifica desde esta sección.
                El usuario conserva su contraseña actual.

            </div>


            {{-- Botones --}}

            <div class="d-flex justify-content-end gap-2 mt-4">

                <a
                    href="{{ route('usuarios.index') }}"
                    class="btn btn-secondary"
                >
                    Cancelar
                </a>


                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    <i class="bi bi-save"></i>
                    Guardar cambios
                </button>

            </div>

        </form>

    </div>

</div>

</div>

@endsection
