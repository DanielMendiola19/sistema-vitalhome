<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CitasAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $usuario = $request->user();

        if (
            !$usuario ||
            !in_array($usuario->rol, [
                'administrador',
                'trabajo_social',
            ], true)
        ) {
            abort(
                403,
                'No tienes permisos para acceder al módulo de citas.'
            );
        }

        return $next($request);
    }
}
