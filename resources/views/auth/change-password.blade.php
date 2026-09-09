@extends('layouts.app')

@section('title', 'Cambiar contraseña | VITALHOME')

@section('content')

<div class="dashboard-header">

    <div>

        <h1>
            Cambiar contraseña
        </h1>

        <p>
            Por seguridad, debes establecer una nueva contraseña
            antes de continuar.
        </p>

    </div>

</div>

<div class="section-card">

    <div class="section-card-header">

        <div>

            <h2>
                Nueva contraseña
            </h2>

            <p>
                Ingresa una contraseña personal y segura.
            </p>

        </div>

    </div>

    <div style="padding: 30px;">

        @if ($errors->any())

            <div
                style="
                    background-color: #fdeeee;
                    border: 1px solid #f2caca;
                    color: #c43d3d;
                    padding: 15px;
                    border-radius: 8px;
                    margin-bottom: 20px;
                "
            >

                <strong>
                    No se pudo actualizar la contraseña.
                </strong>

                <ul style="margin: 8px 0 0 20px;">

                    @foreach ($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif

        <form
            action="{{ route('password.update') }}"
            method="POST"
        >

            @csrf

            {{-- Nueva contraseña --}}
            <div style="margin-bottom: 20px;">

                <label
                    for="password"
                    style="
                        display: block;
                        margin-bottom: 8px;
                        font-weight: 600;
                    "
                >
                    Nueva contraseña
                </label>

                <div style="position: relative;">

                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control"
                        placeholder="Ingresa tu nueva contraseña"
                        required
                        autocomplete="new-password"
                        style="padding-right: 45px;"
                    >

                    <button
                        type="button"
                        onclick="togglePassword('password', this)"
                        style="
                            position: absolute;
                            right: 12px;
                            top: 50%;
                            transform: translateY(-50%);
                            border: none;
                            background: transparent;
                            color: #6b7785;
                            cursor: pointer;
                        "
                        aria-label="Mostrar contraseña"
                    >
                        <i class="bi bi-eye"></i>
                    </button>

                </div>

            </div>

            {{-- Confirmar contraseña --}}
            <div style="margin-bottom: 20px;">

                <label
                    for="password_confirmation"
                    style="
                        display: block;
                        margin-bottom: 8px;
                        font-weight: 600;
                    "
                >
                    Confirmar contraseña
                </label>

                <div style="position: relative;">

                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        class="form-control"
                        placeholder="Repite tu nueva contraseña"
                        required
                        autocomplete="new-password"
                        style="padding-right: 45px;"
                    >

                    <button
                        type="button"
                        onclick="togglePassword('password_confirmation', this)"
                        style="
                            position: absolute;
                            right: 12px;
                            top: 50%;
                            transform: translateY(-50%);
                            border: none;
                            background: transparent;
                            color: #6b7785;
                            cursor: pointer;
                        "
                        aria-label="Mostrar contraseña"
                    >
                        <i class="bi bi-eye"></i>
                    </button>

                </div>

            </div>

            {{-- Requisitos --}}
            <div
                style="
                    background-color: #edf7f0;
                    border: 1px solid #d5eadb;
                    border-radius: 8px;
                    padding: 18px;
                    margin-bottom: 25px;
                "
            >

                <strong style="color: #1e3a5f;">
                    La contraseña debe contener:
                </strong>

                <ul
                    style="
                        margin: 10px 0 0 20px;
                        color: #6b7785;
                    "
                >

                    <li>
                        Al menos 8 caracteres
                    </li>

                    <li>
                        Al menos una letra mayúscula
                    </li>

                    <li>
                        Al menos una letra minúscula
                    </li>

                    <li>
                        Al menos un número o carácter especial
                    </li>

                </ul>

            </div>

            <div
                style="
                    display: flex;
                    justify-content: flex-end;
                    gap: 12px;
                "
            >

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    <i class="bi bi-shield-check"></i>
                    Guardar nueva contraseña
                </button>

            </div>

        </form>

    </div>

</div>

@endsection

@section('scripts')

<script>

function togglePassword(inputId, button) {

    const input = document.getElementById(inputId);
    const icon = button.querySelector('i');

    if (input.type === 'password') {

        input.type = 'text';

        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');

    } else {

        input.type = 'password';

        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');

    }

}

</script>

@endsection
