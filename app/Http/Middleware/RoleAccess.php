<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleAccess
{
    /**
     * Permite el acceso únicamente a los roles indicados
     * en la ruta.
     *
     * Ejemplo:
     *
     * ->middleware('role:administrador,enfermero')
     */
    public function handle(
        Request $request,
        Closure $next,
        string ...$roles
    ): Response {

        $usuario = $request->user();

        if (!$usuario) {
            abort(403, 'No tienes permisos para acceder.');
        }

        if (!in_array($usuario->rol, $roles, true)) {
            abort(
                403,
                'No tienes permisos para acceder a este módulo.'
            );
        }

        return $next($request);
    }
}
