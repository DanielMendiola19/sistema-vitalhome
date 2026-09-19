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
use App\Http\Controllers\CitaController;


/*
|--------------------------------------------------------------------------
| INICIO
|--------------------------------------------------------------------------
*/

Route::get('/', function () {

    return Auth::check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');

});


/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
*/

Route::get(
    '/login',
    [AuthController::class, 'showLogin']
)->name('login');


Route::post(
    '/login',
    [AuthController::class, 'login']
);


/*
|--------------------------------------------------------------------------
| RECUPERAR CONTRASEÑA
|--------------------------------------------------------------------------
*/

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


/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/

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
// TODOS LOS ROLES DEL SISTEMA
// ======================================================

Route::get(
    '/dashboard',
    [DashboardController::class, 'index']
)
    ->middleware([
        'auth',
        'inactivity',
        'must.change.password',
        'role:administrador,doctor,enfermero,trabajo_social'
    ])
    ->name('dashboard');


// ======================================================
// USUARIOS
// SOLO ADMINISTRADOR
// ======================================================

Route::middleware([
    'auth',
    'inactivity',
    'must.change.password',
    'role:administrador'
])->group(function () {

    // Listado
    Route::get(
        '/usuarios',
        [
            UsuarioController::class,
            'index'
        ]
    )->name('usuarios.index');


    // Formulario crear
    Route::get(
        '/usuarios/crear',
        [
            UsuarioController::class,
            'create'
        ]
    )->name('usuarios.create');


    // Guardar
    Route::post(
        '/usuarios',
        [
            UsuarioController::class,
            'store'
        ]
    )->name('usuarios.store');


    // Editar
    Route::get(
        '/usuarios/{id}/editar',
        [
            UsuarioController::class,
            'edit'
        ]
    )->name('usuarios.edit');


    // Actualizar
    Route::put(
        '/usuarios/{id}',
        [
            UsuarioController::class,
            'update'
        ]
    )->name('usuarios.update');


    // Cambiar estado
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


    // Papelera
    Route::get(
        '/usuarios/papelera',
        [
            UsuarioController::class,
            'papelera'
        ]
    )->name('usuarios.papelera');


    // Restaurar
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
// LECTURA:
// ADMINISTRADOR / DOCTOR / ENFERMERO / TRABAJO SOCIAL
// ======================================================

Route::middleware([
    'auth',
    'inactivity',
    'must.change.password',
    'role:administrador,doctor,enfermero,trabajo_social'
])->group(function () {

    // Listado
    Route::get(
        '/pacientes',
        [PacienteController::class, 'index']
    )->name('pacientes.index');


    // Verificar CI en tiempo real (debe ir antes de /pacientes/{id})
    Route::get(
        '/pacientes/verificar-ci',
        [PacienteController::class, 'verificarCi']
    )->name('pacientes.verificar-ci');


    // Ver detalle
    Route::get(
        '/pacientes/{id}',
        [PacienteController::class, 'show']
    )->name('pacientes.show');

});


// ======================================================
// PACIENTES
// MODIFICACIÓN:
// ADMINISTRADOR / DOCTOR / ENFERMERO
//
// TRABAJO SOCIAL NO PUEDE MODIFICAR
// ======================================================

Route::middleware([
    'auth',
    'inactivity',
    'must.change.password',
    'role:administrador,doctor,enfermero'
])->group(function () {

    // Crear
    Route::post(
        '/pacientes',
        [PacienteController::class, 'store']
    )->name('pacientes.store');


    // Actualizar
    Route::put(
        '/pacientes/{id}',
        [PacienteController::class, 'update']
    )->name('pacientes.update');


    // Eliminación lógica
    Route::delete(
        '/pacientes/{id}',
        [PacienteController::class, 'destroy']
    )->name('pacientes.destroy');


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
// ADMINISTRADOR Y ENFERMERO
// ======================================================

Route::middleware([
    'auth',
    'inactivity',
    'must.change.password',
    'role:administrador,enfermero'
])->group(function () {

    // Inventario general
    Route::get(
        '/inventario',
        [
            InventarioController::class,
            'index'
        ]
    )->name('inventario.index');


    // Inventario por pacientes
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


    // Detalle
    Route::get(
        '/inventario/detalle/{inventario}',
        [
            InventarioController::class,
            'detalle'
        ]
    )->name('inventario.detalle');


    // Actualizar inventario
    Route::put(
        '/inventario/{inventario}',
        [
            InventarioController::class,
            'actualizar'
        ]
    )->name('inventario.actualizar');


    // Entrada
    Route::post(
        '/inventario/entrada',
        [
            InventarioController::class,
            'entrada'
        ]
    )->name('inventario.entrada');


    // Salida
    Route::post(
        '/inventario/salida',
        [
            InventarioController::class,
            'salida'
        ]
    )->name('inventario.salida');


    // Transferencia
    Route::post(
        '/inventario/transferir',
        [
            InventarioController::class,
            'transferir'
        ]
    )->name('inventario.transferir');


    // Salida de inventario de paciente
    Route::post(
        '/inventario/paciente/{inventarioPaciente}/salida',
        [
            InventarioController::class,
            'salidaPaciente'
        ]
    )->name('inventario.paciente.salida');


    // Registrar medicamento para paciente
    Route::post(
        '/inventario/paciente/registrar',
        [
            InventarioController::class,
            'registrarInventarioPaciente'
        ]
    )->name('inventario.paciente.registrar');

});


// ======================================================
// MEDICAMENTOS
// ADMINISTRADOR Y ENFERMERO
// ======================================================

Route::middleware([
    'auth',
    'inactivity',
    'must.change.password',
    'role:administrador,enfermero'
])->group(function () {

    Route::get(
        '/medicamentos',
        [
            MedicamentoController::class,
            'index'
        ]
    )->name('medicamentos.index');


    Route::get(
        '/medicamentos/verificar-duplicado',
        [
            MedicamentoController::class,
            'verificarDuplicado'
        ]
    )->name('medicamentos.verificar-duplicado');


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
// ADMINISTRADOR / DOCTOR / ENFERMERO
// ==========================================================

Route::middleware([
    'auth',
    'inactivity',
    'must.change.password',
    'role:administrador,doctor,enfermero'
])->group(function () {

    // Lista general
    Route::get(
        '/tratamientos-kardex',
        [TratamientoKardexController::class, 'indexGeneral']
    )->name('tratamientos_kardex.lista');


    // Kardex de paciente
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


    // Diagnóstico
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
// CITAS
// ADMINISTRADOR / ENFERMERO / TRABAJO SOCIAL
//
// DOCTOR NO PUEDE ACCEDER
// ==========================================================

Route::middleware([
    'auth',
    'inactivity',
    'must.change.password',
    'role:administrador,enfermero,trabajo_social'
])->group(function () {

    // Listado
    Route::get(
        '/citas',
        [CitaController::class, 'index']
    )->name('citas.index');


    // Crear
    Route::get(
        '/citas/crear',
        [CitaController::class, 'create']
    )->name('citas.create');


    // Guardar
    Route::post(
        '/citas',
        [CitaController::class, 'store']
    )->name('citas.store');


    // Editar
    Route::get(
        '/citas/{cita}/editar',
        [CitaController::class, 'edit']
    )->name('citas.edit');


    // Actualizar
    Route::put(
        '/citas/{cita}',
        [CitaController::class, 'update']
    )->name('citas.update');


    // Completar
    Route::patch(
        '/citas/{cita}/completar',
        [CitaController::class, 'completar']
    )->name('citas.completar');


    // Cancelar
    Route::patch(
        '/citas/{cita}/cancelar',
        [CitaController::class, 'cancelar']
    )->name('citas.cancelar');


    // Reactivar
    Route::patch(
        '/citas/{cita}/reactivar',
        [CitaController::class, 'reactivar']
    )->name('citas.reactivar');

});


// ==========================================================
// REPORTES
// ADMINISTRADOR Y ENFERMERO
// ==========================================================

Route::middleware([
    'auth',
    'inactivity',
    'must.change.password',
    'role:administrador,enfermero'
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
