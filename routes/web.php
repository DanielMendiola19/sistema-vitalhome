<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\MedicamentoController;

Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login']);

Route::get('/recuperar-password', [
    AuthController::class,
    'showForgotPassword'
])->name('password.forgot');

Route::post('/recuperar-password', [
    AuthController::class,
    'forgotPassword'
])->name('password.email');

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');


// ======================================================
// CAMBIO OBLIGATORIO DE CONTRASEÑA
// ======================================================

Route::middleware(['auth', 'inactivity', 'must.change.password'])->group(function () {

    Route::get('/cambiar-password', [
        AuthController::class,
        'showChangePassword'
    ])->name('password.change');

    Route::post('/cambiar-password', [
        AuthController::class,
        'changePassword'
    ])->name('password.update');


});



Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'inactivity', 'must.change.password'])
    ->name('dashboard');


// ======================================================
// USUARIOS - SOLO ADMINISTRADOR
// ======================================================
Route::middleware(['auth', 'inactivity', 'admin'])->group(function () {

    // Listado de usuarios
    Route::get('/usuarios', [
        UsuarioController::class,
        'index'
    ])->name('usuarios.index');

    // Formulario para registrar usuario
    Route::get('/usuarios/crear', [
        UsuarioController::class,
        'create'
    ])->name('usuarios.create');

    // Guardar nuevo usuario
    Route::post('/usuarios', [
        UsuarioController::class,
        'store'
    ])->name('usuarios.store');

    // Formulario para editar usuario
    Route::get('/usuarios/{id}/editar', [
        UsuarioController::class,
        'edit'
    ])->name('usuarios.edit');

    // Actualizar usuario
    Route::put('/usuarios/{id}', [
        UsuarioController::class,
        'update'
    ])->name('usuarios.update');


    Route::patch('/usuarios/{id}/estado', [
        UsuarioController::class,
        'cambiarEstado'
    ])->name('usuarios.estado');

    // Eliminación lógica
    Route::delete('/usuarios/{id}', [
        UsuarioController::class,
        'destroy'
    ])->name('usuarios.destroy');


    Route::get('/usuarios/papelera', [
        UsuarioController::class,
        'papelera'
    ])->name('usuarios.papelera');

    Route::patch('/usuarios/{id}/restaurar', [
        UsuarioController::class,
        'restaurar'
    ])->name('usuarios.restaurar');

});



// ======================================================
// PACIENTES
// ======================================================

Route::middleware(['auth', 'inactivity', 'must.change.password'])->group(function () {

    // Listado
    Route::get('/pacientes', [PacienteController::class, 'index'])
        ->name('pacientes.index');

    // Crear
    Route::post('/pacientes', [PacienteController::class, 'store'])
        ->name('pacientes.store');

    // Ver detalle
    Route::get('/pacientes/{id}', [PacienteController::class, 'show'])
        ->name('pacientes.show');

    // Actualizar
    Route::put('/pacientes/{id}', [PacienteController::class, 'update'])
        ->name('pacientes.update');

    // Agregar observación clínica
    Route::post(
        '/pacientes/{id}/observaciones',
        [PacienteController::class, 'storeObservation']
    )->name('pacientes.observaciones.store');

    // Agregar signos vitales
    Route::post(
        '/pacientes/{id}/signos-vitales',
        [PacienteController::class, 'storeSignosVitales']
    )->name('pacientes.signos-vitales.store');
});


// INVENTARIO
Route::middleware(['auth', 'inactivity', 'must.change.password'])->group(function () {

    // Inventario general
    Route::get('/inventario', [
        InventarioController::class,
        'index'
    ])->name('inventario.index');

    // Inventario por paciente
    Route::get('/inventario/pacientes', [
        InventarioController::class,
        'pacientes'
    ])->name('inventario.pacientes');

    // Medicamentos de un paciente
    Route::get('/inventario/pacientes/{paciente}', [
        InventarioController::class,
        'paciente'
    ])->name('inventario.paciente');

    // Detalle de medicamento
    Route::get('/inventario/detalle/{inventario}', [
        InventarioController::class,
        'detalle'
    ])->name('inventario.detalle');

    // Movimientos
    Route::post('/inventario/entrada', [
        InventarioController::class,
        'entrada'
    ])->name('inventario.entrada');

    Route::post('/inventario/salida', [
        InventarioController::class,
        'salida'
    ])->name('inventario.salida');

    Route::post('/inventario/transferir', [
        InventarioController::class,
        'transferir'
    ])->name('inventario.transferir');

    Route::post('/inventario/paciente/{inventarioPaciente}/salida', [
        InventarioController::class,
        'salidaPaciente'
    ])->name('inventario.paciente.salida');



    Route::post('/inventario/paciente/registrar', [
        InventarioController::class,
        'registrarInventarioPaciente'
    ])->name('inventario.paciente.registrar');


    // ======================================================
    // MEDICAMENTOS
    // ======================================================

    Route::get('/medicamentos', [
        MedicamentoController::class,
        'index'
    ])->name('medicamentos.index');

    Route::get('/medicamentos/crear', [
        MedicamentoController::class,
        'create'
    ])->name('medicamentos.create');

    Route::post('/medicamentos', [
        MedicamentoController::class,
        'store'
    ])->name('medicamentos.store');

    Route::get('/medicamentos/{medicamento}/editar', [
        MedicamentoController::class,
        'edit'
    ])->name('medicamentos.edit');

    Route::put('/medicamentos/{medicamento}', [
        MedicamentoController::class,
        'update'
    ])->name('medicamentos.update');
});
