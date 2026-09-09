<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        /*
        |--------------------------------------------------------------------------
        | Verificar que el usuario esté autenticado
        |--------------------------------------------------------------------------
        */

        if (!Auth::check()) {
            return redirect()->route('login');
        }

        /*
        |--------------------------------------------------------------------------
        | Verificar que el usuario sea Administrador
        |--------------------------------------------------------------------------
        */

        if (Auth::user()->rol !== 'administrador') {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        return $next($request);
    }
}
