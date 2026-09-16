<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Registrarse | Vital Homme</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    <style>
        body {
            background-color: #ffffff;
            min-height: 100vh;
        }

        .register-container {
            min-height: 100vh;
            padding: 20px;
        }

        .register-card {
            max-width: 430px;
            width: 100%;
            background-color: #ffffff;
            border: 1px solid #d8d8d8;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .register-card::before {
            content: "";
            display: block;
            width: 50%;
            height: 4px;
            margin: 0 auto;
            background-color: #2424BD;
            border-radius: 0 0 10px 10px;
        }

        .auth-tabs {
            display: flex;
            gap: 5px;
            padding: 5px;
            background-color: #2424BD;
            border-radius: 12px;
        }

        .auth-tab {
            flex: 1;
            padding: 9px 12px;
            text-align: center;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            color: #ffffff;
            border-radius: 9px;
            transition: 0.2s ease;
        }

        .auth-tab:hover {
            color: #ffffff;
            background-color: rgba(255, 255, 255, 0.12);
        }

        .auth-tab.active {
            background-color: #ffffff;
            color: #2424BD;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.12);
        }

        .logo-container {
            width: 100%;
            margin: 0 auto 20px;
            text-align: center;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .logo-container img {
            display: block;
            width: auto;
            max-width: 85%;
            height: 130px;
            object-fit: contain;
            object-position: center;
            margin: 0 auto;
        }

        .system-title {
            margin-top: 8px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 1.5px;
            color: #1E3A5F;
        }

        .title-line {
            width: 45px;
            height: 3px;
            margin: 5px auto 22px;
            background-color: #00BF06;
            border-radius: 5px;
        }

        .form-label {
            margin-bottom: 7px;
            font-weight: 500;
            color: #222222;
        }

        .input-group {
            border-radius: 11px;
            overflow: hidden;
        }

        .input-group-text {
            padding-left: 14px;
            padding-right: 10px;
            background-color: #ffffff;
            border: 1px solid #bdbdbd;
            border-right: none;
            color: #555555;
        }

        .form-control,
        .form-select {
            padding: 11px;
            color: #222222;
            border: 1px solid #bdbdbd;
            border-left: none;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #2424BD;
            box-shadow: none;
        }

        .input-group:focus-within .input-group-text {
            border-color: #2424BD;
            color: #2424BD;
        }

        .btn-register {
            padding: 11px;
            background-color: #2424BD;
            border: 1px solid #2424BD;
            border-radius: 11px;
            color: #ffffff;
            font-weight: 600;
            transition: 0.2s ease;
        }

        .btn-register:hover {
            background-color: #1d1d9f;
            border-color: #1d1d9f;
            color: #ffffff;
            transform: translateY(-1px);
        }

        .btn-register:active {
            transform: translateY(0);
        }

        .alert {
            border-radius: 10px;
            font-size: 14px;
        }

        .register-footer {
            color: #888888;
            font-size: 12px;
        }

        @media (max-width: 576px) {

            .logo-container {
                margin-bottom: 15px;
            }

            .logo-container img {
                max-width: 75%;
                height: 100px;
            }

            .system-title {
                font-size: 11px;
                letter-spacing: 1px;
            }
        }
    </style>
</head>

<body>

    <div class="container register-container d-flex align-items-center justify-content-center">

        <div class="register-card">

            <div class="card-body p-4 p-md-5">

                <!-- Pestañas -->
                <div class="auth-tabs mb-3">

                    <a href="{{ route('login') }}" class="auth-tab">
                        Iniciar sesión
                    </a>

                    <a href="{{ route('register') }}" class="auth-tab active">
                        Registrarse
                    </a>

                </div>

                <!-- Logo -->
                <div class="logo-container">

                    <img
                        src="{{ asset('images/logo-vitalhome.png') }}"
                        alt="VitalHome"
                    >

                    <div class="system-title">
                        SISTEMA DE GESTIÓN
                    </div>

                </div>

                <div class="title-line"></div>

                <!-- Errores -->
                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Formulario -->
                <form method="POST" action="{{ route('register') }}">

                    @csrf

                    <!-- Nombre -->
                    <div class="mb-3">

                        <label for="nombre" class="form-label">
                            Nombre
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="bi bi-person"></i>
                            </span>

                            <input
                                type="text"
                                class="form-control"
                                id="nombre"
                                name="nombre"
                                value="{{ old('nombre') }}"
                                autocomplete="given-name"
                                required
                                autofocus
                            >

                        </div>

                    </div>

                    <!-- Apellido -->
                    <div class="mb-3">

                        <label for="apellido" class="form-label">
                            Apellido
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="bi bi-person"></i>
                            </span>

                            <input
                                type="text"
                                class="form-control"
                                id="apellido"
                                name="apellido"
                                value="{{ old('apellido') }}"
                                autocomplete="family-name"
                                required
                            >

                        </div>

                    </div>

                    <!-- Rol -->
                    <div class="mb-3">

                        <label for="rol" class="form-label">
                            Rol
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="bi bi-person-badge"></i>
                            </span>

                            <select
                                class="form-select"
                                id="rol"
                                name="rol"
                                required
                            >
                                <option value="" disabled {{ old('rol') ? '' : 'selected' }}>
                                    Seleccione un rol
                                </option>

                                <option
                                    value="administrador"
                                    {{ old('rol') == 'administrador' ? 'selected' : '' }}
                                >
                                    Administrador
                                </option>

                                <option
                                    value="enfermero"
                                    {{ old('rol') == 'enfermero' ? 'selected' : '' }}
                                >
                                    Enfermero
                                </option>

                                <option
                                    value="medico"
                                    {{ old('rol') == 'medico' ? 'selected' : '' }}
                                >
                                    Médico
                                </option>


                            </select>

                        </div>

                    </div>

                    <!-- Correo -->
                    <div class="mb-3">

                        <label for="email" class="form-label">
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
                                autocomplete="email"
                                required
                            >

                        </div>

                    </div>

                    <!-- Contraseña -->
                    <div class="mb-3">

                        <label for="password" class="form-label">
                            Contraseña
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="bi bi-lock"></i>
                            </span>

                            <input
                                type="password"
                                class="form-control"
                                id="password"
                                name="password"
                                autocomplete="new-password"
                                required
                            >

                        </div>

                    </div>

                    <!-- Confirmar contraseña -->
                    <div class="mb-4">

                        <label for="password_confirmation" class="form-label">
                            Confirmar contraseña
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="bi bi-lock-fill"></i>
                            </span>

                            <input
                                type="password"
                                class="form-control"
                                id="password_confirmation"
                                name="password_confirmation"
                                autocomplete="new-password"
                                required
                            >

                        </div>

                    </div>

                    <!-- Botón -->
                    <div class="d-grid">

                        <button
                            type="submit"
                            class="btn btn-register"
                        >
                            Registrarse
                        </button>

                    </div>

                </form>

                <div class="text-center mt-4 register-footer">
                    Nuestra Casa Es Tu Hogar © {{ date('Y') }}
                </div>

            </div>

        </div>

    </div>

</body>

</html>
