<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DoctorAccess
{
    /**
     * Controla las rutas permitidas para el rol doctor.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $usuario = $request->user();

        /*
        |--------------------------------------------------------------------------
        | Si no es doctor, no modificamos absolutamente nada
        |--------------------------------------------------------------------------
        |
        | Administrador y demás roles siguen funcionando como antes.
        |
        */
        if (!$usuario || $usuario->rol !== 'doctor') {
            return $next($request);
        }


        /*
        |--------------------------------------------------------------------------
        | RUTAS PERMITIDAS PARA DOCTOR
        |--------------------------------------------------------------------------
        |
        | Dashboard:
        |   - Puede verlo.
        |
        | Pacientes:
        |   - Ver listado
        |   - Ver paciente
        |   - Registrar
        |   - Actualizar
        |   - Observaciones
        |   - Signos vitales
        |
        | Tratamientos / Kardex:
        |   - Ver
        |   - Registrar
        |   - Actualizar
        |   - Finalizar
        |   - Modificar diagnóstico
        |
        | No incluimos ninguna ruta de eliminación.
        |
        */

        $rutasPermitidas = [

    // Dashboard
    'dashboard',

    // Pacientes
    'pacientes.index',
    'pacientes.store',
    'pacientes.show',
    'pacientes.update',
    'pacientes.observaciones.store',
    'pacientes.signos-vitales.store',

    // Tratamientos / Kardex
    'tratamientos_kardex.lista',
    'tratamientos_kardex.index',
    'tratamientos_kardex.store',
    'tratamientos_kardex.update',
    'tratamientos_kardex.diagnostico',
    'tratamientos_kardex.finalizar',
];

        $nombreRuta = $request->route()?->getName();


        /*
        |--------------------------------------------------------------------------
        | Si está permitido, continúa normalmente
        |--------------------------------------------------------------------------
        */

        if (
            $nombreRuta &&
            in_array($nombreRuta, $rutasPermitidas, true)
        ) {
            return $next($request);
        }


        /*
        |--------------------------------------------------------------------------
        | Cualquier otro módulo queda bloqueado para doctor
        |--------------------------------------------------------------------------
        */

        abort(
            403,
            'No tienes permisos para acceder a este módulo.'
        );
    }
}
