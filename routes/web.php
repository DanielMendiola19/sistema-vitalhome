<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\InventarioController;

Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister'])
    ->name('register');

Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'inactivity'])
    ->name('dashboard');


// ======================================================
// PACIENTES
// ======================================================

Route::middleware('auth')->group(function () {

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
Route::middleware('auth')->group(function () {

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
});

