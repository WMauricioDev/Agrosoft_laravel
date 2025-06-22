<?php

use App\Http\Controllers\Usuarios\AuthController;
use App\Http\Controllers\InsumoController;
use App\Http\Controllers\Trazabilidad\TipoActividadController;
use App\Http\Controllers\Trazabilidad\LoteController;
use App\Http\Controllers\Trazabilidad\BancalController;
use App\Http\Controllers\Trazabilidad\TipoEspecieController;
use App\Http\Controllers\Trazabilidad\EspecieController;
use App\Http\Controllers\Trazabilidad\CultivoController;
use App\Http\Controllers\Trazabilidad\UnidadMedidaController;
use App\Http\Controllers\Trazabilidad\CosechaController;
use App\Http\Controllers\Finanzas\SalarioController;
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
    Route::patch('/user/{user}', [UserController::class, 'update']);
    Route::post('/user/secondRegister', [UserController::class, 'store']);




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

    //Lotes
    Route::get('lotes', [LoteController::class, 'index'])
        ->name('lotes.index');
    Route::get('lotes/{lote}', [LoteController::class, 'show'])
        ->name('lotes.show');
    Route::post('lotes', [LoteController::class, 'store'])
        ->name('lotes.store');
    Route::put('lotes/{lote}', [LoteController::class, 'update'])
        ->name('lotes.update');
    Route::delete('lotes/{lote}', [LoteController::class, 'destroy'])
        ->name('lotes.destroy');
        
    //Bancal
    Route::get('Bancal', [BancalController::class, 'index'])
        ->name('Bancal.index');
    Route::get('Bancal/{bancal}', [BancalController::class, 'show'])
        ->name('Bancal.show');
    Route::post('Bancal', [BancalController::class, 'store'])
        ->name('Bancal.store');
    Route::put('Bancal/{bancal}', [BancalController::class, 'update'])
        ->name('Bancal.update');
    Route::delete('Bancal/{bancal}', [BancalController::class, 'destroy'])
        ->name('Bancal.destroy');

    // Tipo Especies
    Route::get('tipo-especies', [TipoEspecieController::class, 'index'])
        ->name('tipo-especies.index');
    Route::get('tipo-especies/{tipoEspecie}', [TipoEspecieController::class, 'show'])
        ->name('tipo-especies.show');
    Route::post('tipo-especies', [TipoEspecieController::class, 'store'])
        ->name('tipo-especies.store');
    Route::put('tipo-especies/{tipoEspecie}', [TipoEspecieController::class, 'update'])
        ->name('tipo-especies.update');
    Route::delete('tipo-especies/{tipoEspecie}', [TipoEspecieController::class, 'destroy'])
        ->name('tipo-especies.destroy');
     // Especies
    Route::get('especies', [EspecieController::class, 'index'])
        ->name('especies.index');
    Route::get('especies/{especie}', [EspecieController::class, 'show'])
        ->name('especies.show');
    Route::post('especies', [EspecieController::class, 'store'])
        ->name('especies.store');
    Route::put('especies/{especie}', [EspecieController::class, 'update'])
        ->name('especies.update');
    Route::delete('especies/{especie}', [EspecieController::class, 'destroy'])
        ->name('especies.destroy');
    // Cultivos
    Route::get('cultivos', [CultivoController::class, 'index'])
        ->name('cultivos.index');
    Route::get('cultivos/{cultivo}', [CultivoController::class, 'show'])
        ->name('cultivos.show');
    Route::post('cultivos', [CultivoController::class, 'store'])
        ->name('cultivos.store');
    Route::put('cultivos/{cultivo}', [CultivoController::class, 'update'])
        ->name('cultivos.update');
    Route::delete('cultivos/{cultivo}', [CultivoController::class, 'destroy'])
        ->name('cultivos.destroy');
    // Cosechas
    Route::get('cosechas', [CosechaController::class, 'index'])
        ->name('cosechas.index');
    Route::get('cosechas/{cosecha}', [CosechaController::class, 'show'])
        ->name('cosechas.show');
    Route::post('cosechas', [CosechaController::class, 'store'])
        ->name('cosechas.store');
    Route::put('cosechas/{cosecha}', [CosechaController::class, 'update'])
        ->name('cosechas.update');
    Route::delete('cosechas/{cosecha}', [CosechaController::class, 'destroy'])
        ->name('cosechas.destroy');    
    // Unidades de Medida
    Route::get('unidad-medidas', [UnidadMedidaController::class, 'index'])
        ->name('unidad-medidas.index');
    Route::get('unidad-medidas/{unidadMedida}', [UnidadMedidaController::class, 'show'])
        ->name('unidad-medidas.show');
    Route::post('unidad-medidas', [UnidadMedidaController::class, 'store'])
        ->name('unidad-medidas.store');
    Route::put('unidad-medidas/{unidadMedida}', [UnidadMedidaController::class, 'update'])
        ->name('unidad-medidas.update');
    Route::delete('unidad-medidas/{unidadMedida}', [UnidadMedidaController::class, 'destroy'])
        ->name('unidad-medidas.destroy');
    // Salarios
    Route::get('salarios', [SalarioController::class, 'index'])
        ->name('salarios.index');
    Route::get('salarios/actuales', [SalarioController::class, 'actuales'])
        ->name('salarios.actuales');
    Route::get('salarios/{salario}', [SalarioController::class, 'show'])
        ->name('salarios.show');
    Route::post('salarios', [SalarioController::class, 'store'])
        ->name('salarios.store')->middleware([IsAdmin::class, IsUserAuth::class]);
    Route::put('salarios/{salario}', [SalarioController::class, 'update'])
        ->name('salarios.update')->middleware([IsAdmin::class, IsUserAuth::class]);
    Route::delete('salarios/{salario}', [SalarioController::class, 'destroy'])
        ->name('salarios.destroy')->middleware([IsAdmin::class, IsUserAuth::class]);               
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