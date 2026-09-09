<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class MustChangePassword
{
    /**
     * Verificar si el usuario debe cambiar su contraseña.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        /*
         * Si el usuario debe cambiar su contraseña,
         * solamente permitimos acceder a la pantalla
         * de cambio de contraseña y al cierre de sesión.
         */
        if (
            $user->debe_cambiar_password &&
            !$request->routeIs('password.change') &&
            !$request->routeIs('password.update') &&
            !$request->routeIs('logout')
        ) {
            return redirect()->route('password.change');
        }

        return $next($request);
    }
}
