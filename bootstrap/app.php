<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use App\Http\Middleware\CheckInactivity;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\MustChangePassword;
use App\Http\Middleware\DoctorAccess;
use App\Http\Middleware\CitasAccess;
use App\Http\Middleware\RoleAccess;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        $middleware->alias([

            'inactivity' => CheckInactivity::class,

            'admin' => AdminMiddleware::class,

            'must.change.password' => MustChangePassword::class,

            /*
            |--------------------------------------------------------------------------
            | MIDDLEWARE ANTERIORES
            |--------------------------------------------------------------------------
            |
            | Los dejamos registrados para no romper nada.
            | Las rutas nuevas usarán principalmente "role".
            |
            */

            'doctor.access' => DoctorAccess::class,

            'citas.access' => CitasAccess::class,

            /*
            |--------------------------------------------------------------------------
            | CONTROL GENERAL POR ROLES
            |--------------------------------------------------------------------------
            */

            'role' => RoleAccess::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions): void {

        //

    })
    ->create();
