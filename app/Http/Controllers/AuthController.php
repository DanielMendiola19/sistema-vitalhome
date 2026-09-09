<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Mail\RecuperacionPassword;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function forgotPassword(Request $request)
    {
        $validated = $request->validate([
            'email' => [
                'required',
                'email',
            ],
        ]);

        $user = User::where('email', $validated['email'])->first();

        if ($user) {

            $passwordTemporal = Str::random(12);

            $user->password = $passwordTemporal;
            $user->debe_cambiar_password = true;
            $user->save();

            Mail::to($user->email)
                ->send(
                    new RecuperacionPassword(
                        $user->nombre,
                        $passwordTemporal
                    )
                );
        }

        return redirect()
            ->route('login')
            ->with(
                'success',
                'Si el correo está registrado, recibirás una contraseña temporal para recuperar tu acceso.'
            );
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

        $request->session()->put('remember_login', $remember);
        $request->session()->put('last_activity', now()->timestamp);

        if ($user->debe_cambiar_password) {
            return redirect()->route('password.change');
        }

        return redirect()->route('dashboard');
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


    public function showChangePassword()
    {
        $user = Auth::user();

        if (!$user->debe_cambiar_password) {
            return redirect()
                ->route('dashboard');
        }

        return view('auth.change-password');
    }

    public function changePassword(Request $request)
    {
        $validated = $request->validate([
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'regex:/[\d\W]/',
            ],
        ], [
            'password.required' => 'La nueva contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.regex' => 'La contraseña debe contener mayúsculas, minúsculas y al menos un número o carácter especial.',
        ]);

        $user = Auth::user();

        $user->password = $validated['password'];
        $user->debe_cambiar_password = false;
        $user->save();

        // Regenerar la sesión después del cambio de contraseña
        $request->session()->regenerate();

        $request->session()->put(
            'remember_login',
            $request->session()->get('remember_login', false)
        );

        $request->session()->put(
            'last_activity',
            now()->timestamp
        );

        return redirect()
            ->route('dashboard')
            ->with(
                'success',
                'Tu contraseña fue actualizada correctamente.'
            );
    }
}
