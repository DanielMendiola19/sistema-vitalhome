<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Bienvenido a VITALHOME</title>

</head>

<body
    style="
        margin: 0;
        padding: 0;
        background-color: #f7f9fb;
        font-family: Arial, Helvetica, sans-serif;
        color: #25313c;
    "
>

    <div
        style="
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(30, 58, 95, 0.08);
        "
    >

        {{-- Encabezado --}}
        <div
            style="
                background-color: #1e3a5f;
                padding: 30px;
                text-align: center;
                color: #ffffff;
            "
        >

            <h1
                style="
                    margin: 0;
                    font-size: 28px;
                "
            >
                VITALHOME
            </h1>

            <p
                style="
                    margin: 8px 0 0;
                    font-size: 14px;
                    opacity: 0.9;
                "
            >
                Sistema de Gestión
            </p>

        </div>

        {{-- Contenido --}}
        <div style="padding: 35px 30px;">

            <h2
                style="
                    margin-top: 0;
                    color: #1e3a5f;
                "
            >
                ¡Bienvenido, {{ $nombre }}!
            </h2>

            <p>
                Se ha creado una cuenta para ti en el
                <strong>Sistema de Gestión VITALHOME</strong>.
            </p>

            <p>
                A continuación encontrarás tus datos de acceso:
            </p>

            {{-- Datos de acceso --}}
            <div
                style="
                    background-color: #f7f9fb;
                    border: 1px solid #e7ebef;
                    border-radius: 8px;
                    padding: 20px;
                    margin: 25px 0;
                "
            >

                <p style="margin: 0 0 12px;">
                    <strong>Correo electrónico:</strong><br>
                    {{ $email }}
                </p>

                <p style="margin: 0;">
                    <strong>Contraseña temporal:</strong><br>

                    <span
                        style="
                            display: inline-block;
                            margin-top: 6px;
                            padding: 8px 12px;
                            background-color: #ffffff;
                            border: 1px solid #dfe5ea;
                            border-radius: 6px;
                            font-family: monospace;
                            font-size: 16px;
                        "
                    >
                        {{ $passwordTemporal }}
                    </span>
                </p>

            </div>

            {{-- Advertencia --}}
            <div
                style="
                    background-color: #fff7df;
                    border-left: 4px solid #d89b00;
                    padding: 15px;
                    margin-bottom: 25px;
                "
            >

                <strong>Importante:</strong>

                <p
                    style="
                        margin: 6px 0 0;
                    "
                >
                    Esta es una contraseña temporal.
                    Al iniciar sesión por primera vez,
                    el sistema te solicitará cambiarla por
                    una contraseña personal y segura.
                </p>

            </div>

            <p>
                Si no reconoces esta cuenta, comunícate con el
                administrador del sistema.
            </p>

            <p style="margin-bottom: 0;">
                Saludos,<br>
                <strong>Equipo VITALHOME</strong>
            </p>

        </div>

        {{-- Pie --}}
        <div
            style="
                background-color: #f7f9fb;
                padding: 20px;
                text-align: center;
                font-size: 12px;
                color: #6b7785;
            "
        >

            Este correo fue generado automáticamente.
            Por favor, no respondas a este mensaje.

        </div>

    </div>

</body>

</html>
