<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PacienteController;


// LOGIN
Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login']);


// LOGOUT
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');


// DASHBOARD
Route::get('/', [DashboardController::class, 'index'])
    ->middleware('auth')
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

