<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        if (!Auth::attempt($credentials, $remember)) {
            return back()
                ->withErrors([
                    'email' => 'El correo electrónico o la contraseña son incorrectos.',
                ])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Verificar estado del usuario
        |--------------------------------------------------------------------------
        */

        if ($user->estado !== 'activo') {

            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withErrors([
                    'email' => 'Este usuario se encuentra inactivo.',
                ])
                ->onlyInput('email');
        }

        /*
        |--------------------------------------------------------------------------
        | Datos utilizados por el control de inactividad
        |--------------------------------------------------------------------------
        */

        $request->session()->put('remember_login', $remember);
        $request->session()->put('last_activity', now()->timestamp);

        return redirect()->route('dashboard');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'apellido' => ['required', 'string', 'max:100'],
            'rol' => [
                'required',
                'in:administrador,enfermero,medico,personal,usuario',
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],
            'password' => [
                'required',
                'min:8',
                'confirmed',
            ],
        ]);

        User::create([
            'nombre' => $validated['nombre'],
            'apellido' => $validated['apellido'],
            'rol' => $validated['rol'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'estado' => 'activo',
        ]);

        return redirect()
            ->route('login')
            ->with(
                'success',
                'Cuenta creada correctamente. Ahora puedes iniciar sesión.'
            );
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function logoutByInactivity(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('session_expired', true);
    }
}
