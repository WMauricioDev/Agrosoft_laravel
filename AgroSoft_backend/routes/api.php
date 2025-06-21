<?php

use App\Http\Controllers\Usuarios\AuthController;
use App\Http\Controllers\InsumoController;
use App\Http\Controllers\Trazabilidad\TipoActividadController;
use App\Http\Controllers\Usuarios\UserController;
use App\Http\Controllers\Usuarios\RolesController;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsUserAuth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Trazabilidad\TipoControlController;

// ── RUTAS PÚBLICAS ────────────────────────────────────────────────────────────

Route::post('login', [AuthController::class, 'login'])
    ->name('auth.login');
    
Route::post('register', [AuthController::class, 'register'])
    ->name('auth.register');

// Listado abierto de insumos
Route::get('elementos', [InsumoController::class, 'index'])
    ->name('elementos.index');

// ── RUTAS PROTEGIDAS (Usuario autenticado) ───────────────────────────────────
        // Usuarios
Route::middleware(IsUserAuth::class)->group(function () {
   // Información del propio usuario
    Route::get('user/me', [AuthController::class, 'getUser'])
        ->name('auth.user');
    // Traer usuario por ID
    Route::get('user/{user}', [UserController::class, 'show'])->name('users.show');    
     // Traer todos los usuarios
    Route::get('user', [UserController::class, 'index'])->name('users.index');
    // Traer los roles
    Route::get('roles', [RolesController::class, 'index'])->name('roles.index');



    // Cerrar sesión
    Route::post('logout', [AuthController::class, 'logout'])
        ->name('auth.logout');

    // Refrescar token
    Route::post('refresh', [AuthController::class, 'refresh'])
        ->name('auth.refresh');

        // Trazabilidad
    // Listado abierto de tipos de actividad
    Route::get('tipo-actividades', [TipoActividadController::class, 'index'])
        ->name('tipo-actividades.index');
    // Traer tipo de actividad por ID
    Route::get('tipo-actividades/{tipoActividad}', [TipoActividadController::class, 'show'])
        ->name('tipo-actividades.show');
    Route::post('tipo-actividades', [TipoActividadController::class, 'store'])
            ->name('tipo-actividades.store');
    Route::put('tipo-actividades/{tipoActividad}', [TipoActividadController::class, 'update'])
            ->name('tipo-actividades.update');
    Route::delete('tipo-actividades/{tipoActividad}', [TipoActividadController::class, 'destroy'])
            ->name('tipo-actividades.destroy');    
  // Traer y registrar tipo de control
    Route::apiResource('tipo_control', TipoControlController::class);

    Route::get('/tipo_control/{id}', [TipoControlController::class, 'show']);
    Route::put('/tipo_control/{id}', [TipoControlController::class, 'update']);
    Route::delete('/tipo_control/{id}', [TipoControlController::class, 'destroy']);
    // ── Subgrupo: sólo administradores pueden modificar insumos y tipos de actividad ────────────
    Route::middleware(IsAdmin::class)->group(function () {
        // Insumos
        Route::post('insumos', [InsumoController::class, 'store'])
            ->name('insumos.store');
        Route::get('insumos/{insumos}', [InsumoController::class, 'show'])
            ->name('insumos.show');
        Route::put('insumos/{insumos}', [InsumoController::class, 'update'])
            ->name('insumos.update');
        Route::delete('insumos/{elemento}', [InsumoController::class, 'destroy'])
            ->name('insumos.destroy');

        // Tipos de actividad
        //Route::post('tipo-actividades', [TipoActividadController::class, 'store'])
            //->name('tipo-actividades.store');
        //Route::put('tipo-actividades/{tipoActividad}', [TipoActividadController::class, 'update'])
            //->name('tipo-actividades.update');
        //Route::delete('tipo-actividades/{tipoActividad}', [TipoActividadController::class, 'destroy'])
            //->name('tipo-actividades.destroy');
    });
});