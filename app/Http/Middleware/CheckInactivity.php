<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckInactivity
{
    public function handle(Request $request, Closure $next): Response
    {
        /*
        |--------------------------------------------------------------------------
        | Si no hay usuario autenticado
        |--------------------------------------------------------------------------
        */

        if (!Auth::check()) {
            return $next($request);
        }

        /*
        |--------------------------------------------------------------------------
        | Obtener el tiempo de inactividad permitido
        |--------------------------------------------------------------------------
        |
        | Recordarme:
        |     8 minutos
        |
        | Sin recordarme:
        |     2 minutos
        |
        */

        $rememberLogin = $request->session()->get('remember_login', false);

        $timeout = $rememberLogin
            ? 8 * 60
            : 2 * 60;

        /*
        |--------------------------------------------------------------------------
        | Última actividad
        |--------------------------------------------------------------------------
        */

        $lastActivity = $request->session()->get(
            'last_activity',
            now()->timestamp
        );

        /*
        |--------------------------------------------------------------------------
        | Comprobar inactividad
        |--------------------------------------------------------------------------
        */

        if ((now()->timestamp - $lastActivity) >= $timeout) {

            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->with('session_expired', true);
        }

        /*
        |--------------------------------------------------------------------------
        | Actualizar actividad
        |--------------------------------------------------------------------------
        */

        $request->session()->put(
            'last_activity',
            now()->timestamp
        );

        return $next($request);
    }
}
