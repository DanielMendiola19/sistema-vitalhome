<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PacienteController;

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

