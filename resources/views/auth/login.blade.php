<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Iniciar sesión | Vital Homme</title>

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

        .login-container {
            min-height: 100vh;
            padding: 20px;
        }

        .login-card {
            max-width: 430px;
            width: 100%;
            background-color: #ffffff;
            border: 1px solid #d8d8d8;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .login-card::before {
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
            position: relative;
            width: calc(100% + 48px);
            margin-left: -24px;
            margin-top: -15px;
            margin-bottom: 5px;
            text-align: center;
            overflow: hidden;
        }

        .logo-container img {
            display: block;
            width: 120%;
            max-width: none;
            height: 230px;
            margin-left: -10%;
            object-fit: cover;
            object-position: center;
        }

        .system-title {
            position: absolute;
            left: 50%;
            bottom: 8px;
            transform: translateX(-50%);
            width: 100%;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 1.5px;
            color: #333333;
            white-space: nowrap;
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

        .form-control {
            padding: 11px;
            color: #222222;
            border: 1px solid #bdbdbd;
            border-left: none;
        }

        .form-control:focus {
            border-color: #2424BD;
            box-shadow: none;
        }

        .input-group:focus-within .input-group-text {
            border-color: #2424BD;
            color: #2424BD;
        }

        .form-check {
            padding-left: 28px;
        }

        .form-check-input {
            border: 1px solid #777777;
            cursor: pointer;
        }

        .form-check-input:checked {
            background-color: #00BF06;
            border-color: #00BF06;
        }

        .form-check-label {
            color: #444444;
            cursor: pointer;
        }

        .btn-login {
            padding: 11px;
            background-color: #2424BD;
            border: 1px solid #2424BD;
            border-radius: 11px;
            color: #ffffff;
            font-weight: 600;
            transition: 0.2s ease;
        }

        .btn-login:hover {
            background-color: #1d1d9f;
            border-color: #1d1d9f;
            color: #ffffff;
            transform: translateY(-1px);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .alert {
            border-radius: 10px;
            font-size: 14px;
        }

        .login-footer {
            color: #888888;
            font-size: 12px;
        }
    </style>
</head>

<body>

    <div class="container login-container d-flex align-items-center justify-content-center">

        <div class="login-card">

            <div class="card-body p-4 p-md-5">

                <div class="auth-tabs mb-3">
                    <a href="{{ url('/login') }}" class="auth-tab active">
                        Iniciar sesión
                    </a>

                    <a href="#" class="auth-tab">
                        Registrarse
                    </a>
                </div>

                <div class="logo-container">

                    <img
                        src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR7qgs_htJ00_k5Bk2aTNCDHvGRu7GycEjeQNub6UX6TTfxAZYy_00TAsU&s=10"
                        alt="Vital Homme"
                    >

                    <div class="system-title">
                        SISTEMA DE GESTIÓN
                    </div>

                </div>

                <div class="title-line"></div>

                @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ url('/login') }}">

                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">
                            Usuario
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="bi bi-person"></i>
                            </span>

                            <input
                                type="email"
                                class="form-control"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                            >

                        </div>
                    </div>

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
                                required
                            >

                        </div>
                    </div>

                    <div class="form-check mb-4">

                        <input
                            class="form-check-input"
                            type="checkbox"
                            id="remember"
                            name="remember"
                        >

                        <label class="form-check-label" for="remember">
                            Recordarme
                        </label>

                    </div>

                    <div class="d-grid">

                        <button
                            type="submit"
                            class="btn btn-login"
                        >
                            Ingresar
                        </button>

                    </div>

                </form>

                <div class="text-center mt-4 login-footer">
                    Vital Homme © {{ date('Y') }}
                </div>

            </div>

        </div>

    </div>

</body>

</html>
