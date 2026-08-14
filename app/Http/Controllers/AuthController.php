<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();

            // Verificar si el usuario está activo
            if (Auth::user()->estado !== 'activo') {

                Auth::logout();

                return back()->withErrors([
                    'email' => 'Este usuario se encuentra inactivo.',
                ])->onlyInput('email');
            }

            // Login correcto → Dashboard
            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'email' => 'El correo electrónico o la contraseña son incorrectos.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
