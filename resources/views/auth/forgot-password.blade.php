@extends('layouts.app')

@section('title', 'Recuperar contraseña | VITALHOME')

@section('content')

<div
    style="
        min-height: 80vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
    "
>

<div
    style="
        width: 100%;
        max-width: 450px;
    "
>

    {{-- ENCABEZADO --}}

    <div
        style="
            text-align: center;
            margin-bottom: 30px;
        "
    >

        <div
            style="
                width: 64px;
                height: 64px;
                margin: 0 auto 18px;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 50%;
                background-color: #edf7f0;
                color: #198754;
                font-size: 28px;
            "
        >
            <i class="bi bi-key"></i>
        </div>

        <h1
            style="
                color: var(--vh-blue);
                font-size: 27px;
                font-weight: 700;
                margin-bottom: 10px;
            "
        >
            Recuperar contraseña
        </h1>

        <p
            style="
                color: var(--vh-text-muted);
                font-size: 14px;
                line-height: 1.6;
                margin: 0;
            "
        >
            Ingresa el correo electrónico asociado a tu cuenta
            y te enviaremos una contraseña temporal.
        </p>

    </div>


    {{-- TARJETA --}}

    <div class="section-card">

        <div style="padding: 30px;">

            @if ($errors->any())

                <div
                    style="
                        background-color: #fdeeee;
                        border: 1px solid #f2caca;
                        color: #c43d3d;
                        padding: 14px 16px;
                        border-radius: 8px;
                        margin-bottom: 20px;
                        font-size: 14px;
                    "
                >

                    <i class="bi bi-exclamation-circle"></i>

                    {{ $errors->first('email') }}

                </div>

            @endif


            @if (session('success'))

                <div
                    style="
                        background-color: #edf7f0;
                        border: 1px solid #d5eadb;
                        color: #198754;
                        padding: 14px 16px;
                        border-radius: 8px;
                        margin-bottom: 20px;
                        font-size: 14px;
                    "
                >

                    <i class="bi bi-check-circle"></i>

                    {{ session('success') }}

                </div>

            @endif


            <form
                action="{{ route('password.email') }}"
                method="POST"
            >

                @csrf


                {{-- CORREO --}}

                <div style="margin-bottom: 22px;">

                    <label
                        for="email"
                        style="
                            display: block;
                            margin-bottom: 8px;
                            font-weight: 600;
                            color: var(--vh-blue);
                        "
                    >
                        Correo electrónico
                    </label>


                    <div class="input-group">

                        <span class="input-group-text">
                            <i class="bi bi-envelope"></i>
                        </span>

                        <input
                            type="email"
                            class="form-control"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="Ingresa tu correo electrónico"
                            required
                            autocomplete="email"
                        >

                    </div>

                </div>


                {{-- BOTÓN --}}

                <button
                    type="submit"
                    class="btn btn-primary w-100"
                >

                    <i class="bi bi-send"></i>

                    Enviar contraseña temporal

                </button>


                {{-- VOLVER AL LOGIN --}}

                <div
                    style="
                        text-align: center;
                        margin-top: 20px;
                    "
                >

                    <a
                        href="{{ route('login') }}"
                        style="
                            color: var(--vh-blue);
                            font-size: 14px;
                            text-decoration: none;
                            font-weight: 600;
                        "
                    >

                        <i class="bi bi-arrow-left"></i>

                        Volver al inicio de sesión

                    </a>

                </div>

            </form>

        </div>

    </div>

</div>


</div>

@endsection
