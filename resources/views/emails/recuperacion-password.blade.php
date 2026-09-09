<!DOCTYPE html>

<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Recuperación de contraseña - VITALHOME</title>

</head>

<body
    style="
        margin: 0;
        padding: 0;
        background-color: #f4f6f8;
        font-family: Arial, Helvetica, sans-serif;
        color: #333333;
    "
>

<div
    style="
        max-width: 600px;
        margin: 40px auto;
        background-color: #ffffff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    "
>

    {{-- ENCABEZADO --}}

    <div
        style="
            background-color: #1e3a5f;
            padding: 30px;
            text-align: center;
        "
    >

        <h1
            style="
                margin: 0;
                color: #ffffff;
                font-size: 26px;
            "
        >
            VITALHOME
        </h1>

        <p
            style="
                margin: 8px 0 0;
                color: #dbe7f2;
                font-size: 14px;
            "
        >
            Recuperación de contraseña
        </p>

    </div>


    {{-- CONTENIDO --}}

    <div style="padding: 35px 30px;">

        <h2
            style="
                margin-top: 0;
                color: #1e3a5f;
                font-size: 21px;
            "
        >
            Hola, {{ $nombre }}
        </h2>

        <p
            style="
                font-size: 15px;
                line-height: 1.6;
                color: #555555;
            "
        >
            Hemos recibido una solicitud para recuperar la contraseña
            de tu cuenta en el sistema VITALHOME.
        </p>

        <p
            style="
                font-size: 15px;
                line-height: 1.6;
                color: #555555;
            "
        >
            Se ha generado una nueva contraseña temporal para que
            puedas acceder nuevamente a tu cuenta.
        </p>


        {{-- CONTRASEÑA TEMPORAL --}}

        <div
            style="
                margin: 25px 0;
                padding: 20px;
                background-color: #f4f8fb;
                border: 1px solid #d8e3ec;
                border-radius: 10px;
                text-align: center;
            "
        >

            <p
                style="
                    margin: 0 0 8px;
                    color: #6b7785;
                    font-size: 13px;
                "
            >
                Tu contraseña temporal es:
            </p>

            <div
                style="
                    font-size: 22px;
                    font-weight: 700;
                    letter-spacing: 2px;
                    color: #1e3a5f;
                "
            >
                {{ $passwordTemporal }}
            </div>

        </div>


        <p
            style="
                font-size: 14px;
                line-height: 1.6;
                color: #555555;
            "
        >
            Utiliza esta contraseña para iniciar sesión en VITALHOME.
            Por seguridad, el sistema te solicitará establecer una
            nueva contraseña después de iniciar sesión.
        </p>


        <div
            style="
                margin-top: 25px;
                padding: 15px;
                background-color: #fff8e6;
                border: 1px solid #f0dfad;
                border-radius: 8px;
                color: #6b5a2b;
                font-size: 13px;
                line-height: 1.5;
            "
        >

            <strong>Importante:</strong>

            Si no solicitaste recuperar tu contraseña,
            puedes ignorar este mensaje y comunicarte con el
            administrador del sistema.

        </div>

    </div>


    {{-- PIE --}}

    <div
        style="
            padding: 20px 30px;
            background-color: #f8f9fa;
            text-align: center;
            border-top: 1px solid #eeeeee;
        "
    >

        <p
            style="
                margin: 0;
                color: #8a949e;
                font-size: 12px;
            "
        >
            Este mensaje fue enviado automáticamente por el sistema
            VITALHOME.
        </p>

    </div>

</div>


</body>

</html>
