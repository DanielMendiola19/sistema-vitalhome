<?php

namespace App\Http\Controllers;

use App\Mail\UsuarioRegistrado;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UsuarioController extends Controller
{
    /**
     * Mostrar listado de usuarios.
     */
    public function index()
    {
        $usuarios = User::orderBy('nombre')
            ->orderBy('apellido')
            ->get();

        return view('usuarios.index', compact('usuarios'));
    }


    /**
     * Mostrar papelera de usuarios.
     */
    public function papelera()
    {
        $usuarios = User::onlyTrashed()
            ->orderBy('deleted_at', 'desc')
            ->get();

        return view('usuarios.papelera', compact('usuarios'));
    }


    /**
     * Restaurar usuario eliminado.
     */
    public function restaurar(string $id)
    {
        $usuario = User::onlyTrashed()->findOrFail($id);

        $usuario->restore();

        return redirect()
            ->route('usuarios.papelera')
            ->with(
                'success',
                'El usuario fue restaurado correctamente.'
            );
    }


    /**
     * Mostrar formulario para crear un nuevo usuario.
     */
    public function create()
    {
        return view('usuarios.create');
    }


    /**
     * Guardar nuevo usuario.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'nombre' => [
                'required',
                'string',
                'max:100'
            ],

            'apellido' => [
                'required',
                'string',
                'max:100'
            ],

            'rol' => [
                'required',
                'in:administrador,enfermero,doctor,personal,trabajo_social',
            ],

            'email' => [
                'required',
                'email',
                'max:150',
                'unique:users,email',
            ],

        ]);


        // Generar contraseña temporal
        $passwordTemporal = Str::random(12);


        // Crear usuario
        $usuario = User::create([

            'nombre' => $validated['nombre'],

            'apellido' => $validated['apellido'],

            'rol' => $validated['rol'],

            'email' => $validated['email'],

            'password' => $passwordTemporal,

            'estado' => 'activo',

            'debe_cambiar_password' => true,

        ]);


        // Enviar correo con los datos de acceso
        Mail::to($usuario->email)
            ->send(
                new UsuarioRegistrado(
                    $usuario->nombre,
                    $usuario->email,
                    $passwordTemporal
                )
            );


        return redirect()
            ->route('usuarios.index')
            ->with(
                'success',
                'Usuario registrado correctamente. Se enviaron sus datos de acceso al correo electrónico.'
            );
    }


    /**
     * Mostrar usuario.
     */
    public function show(string $id)
    {
        //
    }


    /**
     * Mostrar formulario para editar usuario.
     */
    public function edit(string $id)
    {
        $usuario = User::findOrFail($id);

        return view(
            'usuarios.edit',
            compact('usuario')
        );
    }


    /**
     * Actualizar usuario.
     */
    public function update(
        Request $request,
        string $id
    ) {
        $usuario = User::findOrFail($id);


        $validated = $request->validate([

            'nombre' => [
                'required',
                'string',
                'max:100'
            ],

            'apellido' => [
                'required',
                'string',
                'max:100'
            ],

            'rol' => [
                'required',
                'in:administrador,enfermero,doctor,personal,trabajo_social',
            ],

            'email' => [
                'required',
                'email',
                'max:150',
                'unique:users,email,' . $usuario->id,
            ],

            'estado' => [
                'required',
                'in:activo,inactivo',
            ],

        ]);


        $usuario->update($validated);


        return redirect()
            ->route('usuarios.index')
            ->with(
                'success',
                'Usuario actualizado correctamente.'
            );
    }


    /**
     * Cambiar estado del usuario.
     */
    public function cambiarEstado(string $id)
    {
        $usuario = User::findOrFail($id);


        $usuario->estado =
            $usuario->estado === 'activo'
                ? 'inactivo'
                : 'activo';


        $usuario->save();


        return redirect()
            ->route('usuarios.index')
            ->with(
                'success',
                'El estado del usuario fue actualizado correctamente.'
            );
    }


    /**
     * Eliminación lógica del usuario.
     */
    public function destroy(string $id)
    {
        $usuario = User::findOrFail($id);


        // Evitar que el administrador elimine su propia cuenta
        if ($usuario->id === auth()->id()) {

            return redirect()
                ->route('usuarios.index')
                ->with(
                    'error',
                    'No puedes eliminar tu propia cuenta de administrador.'
                );
        }


        // Eliminación lógica
        $usuario->delete();


        return redirect()
            ->route('usuarios.index')
            ->with(
                'success',
                'Usuario eliminado correctamente.'
            );
    }
}
