<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\MedicamentoController;
use App\Http\Controllers\TratamientoKardexController;
use App\Http\Controllers\ReporteController;


Route::get('/', function () {

    return Auth::check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');

});


Route::get(
    '/login',
    [AuthController::class, 'showLogin']
)->name('login');


Route::post(
    '/login',
    [AuthController::class, 'login']
);


Route::get(
    '/recuperar-password',
    [
        AuthController::class,
        'showForgotPassword'
    ]
)->name('password.forgot');


Route::post(
    '/recuperar-password',
    [
        AuthController::class,
        'forgotPassword'
    ]
)->name('password.email');


Route::post(
    '/logout',
    [AuthController::class, 'logout']
)
    ->middleware('auth')
    ->name('logout');


// ======================================================
// CAMBIO OBLIGATORIO DE CONTRASEÑA
// ======================================================

Route::middleware([
    'auth',
    'inactivity',
    'must.change.password'
])->group(function () {

    Route::get(
        '/cambiar-password',
        [
            AuthController::class,
            'showChangePassword'
        ]
    )->name('password.change');


    Route::post(
        '/cambiar-password',
        [
            AuthController::class,
            'changePassword'
        ]
    )->name('password.update');

});


// ======================================================
// DASHBOARD
// ======================================================

Route::get(
    '/dashboard',
    [DashboardController::class, 'index']
)
    ->middleware([
        'auth',
        'inactivity',
        'must.change.password'
    ])
    ->name('dashboard');


// ======================================================
// USUARIOS - SOLO ADMINISTRADOR
// ======================================================

Route::middleware([
    'auth',
    'inactivity',
    'admin'
])->group(function () {

    // Listado de usuarios
    Route::get(
        '/usuarios',
        [
            UsuarioController::class,
            'index'
        ]
    )->name('usuarios.index');


    // Formulario para registrar usuario
    Route::get(
        '/usuarios/crear',
        [
            UsuarioController::class,
            'create'
        ]
    )->name('usuarios.create');


    // Guardar nuevo usuario
    Route::post(
        '/usuarios',
        [
            UsuarioController::class,
            'store'
        ]
    )->name('usuarios.store');


    // Formulario para editar usuario
    Route::get(
        '/usuarios/{id}/editar',
        [
            UsuarioController::class,
            'edit'
        ]
    )->name('usuarios.edit');


    // Actualizar usuario
    Route::put(
        '/usuarios/{id}',
        [
            UsuarioController::class,
            'update'
        ]
    )->name('usuarios.update');


    Route::patch(
        '/usuarios/{id}/estado',
        [
            UsuarioController::class,
            'cambiarEstado'
        ]
    )->name('usuarios.estado');


    // Eliminación lógica
    Route::delete(
        '/usuarios/{id}',
        [
            UsuarioController::class,
            'destroy'
        ]
    )->name('usuarios.destroy');


    Route::get(
        '/usuarios/papelera',
        [
            UsuarioController::class,
            'papelera'
        ]
    )->name('usuarios.papelera');


    Route::patch(
        '/usuarios/{id}/restaurar',
        [
            UsuarioController::class,
            'restaurar'
        ]
    )->name('usuarios.restaurar');

});


// ======================================================
// PACIENTES
// ======================================================

Route::middleware([
    'auth',
    'inactivity',
    'must.change.password'
])->group(function () {

    // Listado
    Route::get(
        '/pacientes',
        [PacienteController::class, 'index']
    )->name('pacientes.index');


    // Crear
    Route::post(
        '/pacientes',
        [PacienteController::class, 'store']
    )->name('pacientes.store');


    // Ver detalle
    Route::get(
        '/pacientes/{id}',
        [PacienteController::class, 'show']
    )->name('pacientes.show');


    // Actualizar
    Route::put(
        '/pacientes/{id}',
        [PacienteController::class, 'update']
    )->name('pacientes.update');


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


// ======================================================
// INVENTARIO
// ======================================================

Route::middleware([
    'auth',
    'inactivity',
    'must.change.password'
])->group(function () {

    // Inventario general
    Route::get(
        '/inventario',
        [
            InventarioController::class,
            'index'
        ]
    )->name('inventario.index');


    // Inventario por paciente
    Route::get(
        '/inventario/pacientes',
        [
            InventarioController::class,
            'pacientes'
        ]
    )->name('inventario.pacientes');


    // Medicamentos de un paciente
    Route::get(
        '/inventario/pacientes/{paciente}',
        [
            InventarioController::class,
            'paciente'
        ]
    )->name('inventario.paciente');


    // Detalle de medicamento
    Route::get(
        '/inventario/detalle/{inventario}',
        [
            InventarioController::class,
            'detalle'
        ]
    )->name('inventario.detalle');


    Route::put(
        '/inventario/{inventario}',
        [
            InventarioController::class,
            'actualizar'
        ]
    )->name('inventario.actualizar');


    // Movimientos
    Route::post(
        '/inventario/entrada',
        [
            InventarioController::class,
            'entrada'
        ]
    )->name('inventario.entrada');


    Route::post(
        '/inventario/salida',
        [
            InventarioController::class,
            'salida'
        ]
    )->name('inventario.salida');


    Route::post(
        '/inventario/transferir',
        [
            InventarioController::class,
            'transferir'
        ]
    )->name('inventario.transferir');


    Route::post(
        '/inventario/paciente/{inventarioPaciente}/salida',
        [
            InventarioController::class,
            'salidaPaciente'
        ]
    )->name('inventario.paciente.salida');


    Route::post(
        '/inventario/paciente/registrar',
        [
            InventarioController::class,
            'registrarInventarioPaciente'
        ]
    )->name('inventario.paciente.registrar');


    // ======================================================
    // MEDICAMENTOS
    // ======================================================

    Route::get(
        '/medicamentos',
        [
            MedicamentoController::class,
            'index'
        ]
    )->name('medicamentos.index');


    Route::get(
        '/medicamentos/crear',
        [
            MedicamentoController::class,
            'create'
        ]
    )->name('medicamentos.create');


    Route::post(
        '/medicamentos',
        [
            MedicamentoController::class,
            'store'
        ]
    )->name('medicamentos.store');


    Route::get(
        '/medicamentos/{medicamento}/editar',
        [
            MedicamentoController::class,
            'edit'
        ]
    )->name('medicamentos.edit');


    Route::put(
        '/medicamentos/{medicamento}',
        [
            MedicamentoController::class,
            'update'
        ]
    )->name('medicamentos.update');

});


// ==========================================================
// TRATAMIENTOS / KARDEX
// ==========================================================

Route::middleware([
    'auth',
    'inactivity',
    'must.change.password'
])->group(function () {

    // Lista general de pacientes para Tratamientos / Kardex
    Route::get(
        '/tratamientos-kardex',
        [TratamientoKardexController::class, 'indexGeneral']
    )->name('tratamientos_kardex.lista');


    // Kardex de un paciente específico
    Route::get(
        '/pacientes/{paciente}/tratamientos-kardex',
        [TratamientoKardexController::class, 'index']
    )->name('tratamientos_kardex.index');


    // Registrar tratamiento
    Route::post(
        '/pacientes/{paciente}/tratamientos-kardex',
        [TratamientoKardexController::class, 'store']
    )->name('tratamientos_kardex.store');


    // Actualizar tratamiento
    Route::put(
        '/pacientes/{paciente}/tratamientos-kardex/{tratamiento}',
        [TratamientoKardexController::class, 'update']
    )->name('tratamientos_kardex.update');


    // Actualizar diagnóstico del paciente
    Route::patch(
        '/pacientes/{paciente}/tratamientos-kardex/diagnostico',
        [TratamientoKardexController::class, 'actualizarDiagnostico']
    )->name('tratamientos_kardex.diagnostico');


    // Finalizar tratamiento
    Route::patch(
        '/pacientes/{paciente}/tratamientos-kardex/{tratamiento}/finalizar',
        [TratamientoKardexController::class, 'finalizar']
    )->name('tratamientos_kardex.finalizar');

});


// ==========================================================
// REPORTES - SOLO ADMINISTRADOR
// ==========================================================

Route::middleware([
    'auth',
    'inactivity',
    'must.change.password',
    'admin'
])->group(function () {

    Route::get(
        '/reportes',
        [ReporteController::class, 'index']
    )->name('reportes.index');


    Route::get(
        '/reportes/pacientes/{paciente}',
        [ReporteController::class, 'paciente']
    )->name('reportes.paciente');


    Route::get(
        '/reportes/pacientes/{paciente}/informacion-general',
        [ReporteController::class, 'informacionGeneral']
    )->name('reportes.informacion_general');


    /*
     * PDF de Información General
     *
     * ESTA ES LA ÚNICA RUTA NUEVA QUE NECESITÁBAMOS AGREGAR.
     */
    Route::get(
        '/reportes/pacientes/{paciente}/informacion-general/pdf',
        [ReporteController::class, 'informacionGeneralPdf']
    )->name('reportes.informacion_general.pdf');


    Route::get(
        '/reportes/pacientes/{paciente}/kardex',
        [ReporteController::class, 'kardex']
    )->name('reportes.kardex');
    Route::get(
    '/reportes/pacientes/{paciente}/kardex/pdf',
    [ReporteController::class, 'kardexPdf']
)->name('reportes.kardex.pdf');


    Route::get(
        '/reportes/pacientes/{paciente}/historia-clinica',
        [ReporteController::class, 'historiaClinica']
    )->name('reportes.historia_clinica');


    Route::get(
        '/reportes/pacientes/{paciente}/signos-vitales',
        [ReporteController::class, 'signosVitales']
    )->name('reportes.signos_vitales');


    Route::get(
        '/reportes/pacientes/{paciente}/general',
        [ReporteController::class, 'general']
    )->name('reportes.general');

    Route::get(
    '/reportes/pacientes/{paciente}/historia-clinica/pdf',
    [ReporteController::class, 'historiaClinicaPdf']
)->name('reportes.historia_clinica.pdf');


Route::get(
    '/reportes/pacientes/{paciente}/signos-vitales/pdf',
    [ReporteController::class, 'signosVitalesPdf']
)->name('reportes.signos_vitales.pdf');


Route::get(
    '/reportes/pacientes/{paciente}/general/pdf',
    [ReporteController::class, 'generalPdf']
)->name('reportes.general.pdf');
});
