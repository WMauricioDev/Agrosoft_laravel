<?php

use App\Http\Controllers\Usuarios\AuthController;
use App\Http\Controllers\Trazabilidad\TipoActividadController;
use App\Http\Controllers\Trazabilidad\LoteController;
use App\Http\Controllers\Trazabilidad\BancalController;
use App\Http\Controllers\Trazabilidad\TipoEspecieController;
use App\Http\Controllers\Trazabilidad\EspecieController;
use App\Http\Controllers\Trazabilidad\CultivoController;
use App\Http\Controllers\Trazabilidad\UnidadMedidaController;
use App\Http\Controllers\Trazabilidad\CosechaController;
use App\Http\Controllers\Trazabilidad\TipoResiduoController;
use App\Http\Controllers\Trazabilidad\ResiduoController;
use App\Http\Controllers\Trazabilidad\TipoPlagaController;
use App\Http\Controllers\Finanzas\SalarioController;
use App\Http\Controllers\Usuarios\UserController;
use App\Http\Controllers\Usuarios\RolesController;
use App\Http\Controllers\Usuarios\ImportUsuarioController;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsUserAuth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Trazabilidad\TipoControlController;
use App\Http\Controllers\Trazabilidad\ControlesController;
use App\Http\Controllers\Inventario\BodegaController;
use App\Http\Controllers\Inventario\TipoInsumoController;
use App\Http\Controllers\Inventario\InsumoController;
use App\Http\Controllers\Inventario\BodegaInsumoController;
use App\Http\Controllers\Inventario\PrecioProductoController;
use App\Http\Controllers\Inventario\HerramientaController;
use App\Http\Controllers\Inventario\BodegaHerramientaController;
use App\Http\Controllers\Trazabilidad\PlagaController;
use App\Http\Controllers\Trazabilidad\AfeccionController;
use App\Http\Controllers\Trazabilidad\ActividadesController;
use App\Http\Controllers\Trazabilidad\PrestamoInsumoController;
use App\Http\Controllers\Trazabilidad\PrestamoHerramientaController;
use App\Http\Controllers\Finanzas\PagoController;
use App\Http\Controllers\Finanzas\VentaController;
use App\Http\Controllers\IoT\TipoSensorController;
use App\Http\Controllers\IoT\SensorController;
use App\Http\Controllers\IoT\DatoMeteorologicoController;
use App\Http\Controllers\IoT\DatoHistoricoController;
use App\Http\Controllers\Usuarios\CambiarPasswordController;



// ── RUTAS PÚBLICAS ────────────────────────────────────────────────────────────

Route::post('login', [AuthController::class, 'login'])
    ->name('auth.login');

Route::post('register', [AuthController::class, 'register'])
    ->name('auth.register');

// Listado abierto de insumos
Route::get('elementos', [InsumoController::class, 'index'])
    ->name('elementos.index');

// ── RUTAS PROTEGIDAS (Usuario autenticado) ───────────────────────────────────
Route::middleware(IsUserAuth::class)->group(function () {
    // Información del propio usuario
    Route::get('user/me', [AuthController::class, 'getUser'])
        ->name('auth.user');
    // Traer usuario por ID
    Route::get('user/{user}', [UserController::class, 'show'])->name('users.show');
    // Traer todos los usuarios
    Route::get('user', [UserController::class, 'index'])->name('users.index');
Route::middleware('auth:api')->post('/user/password', [CambiarPasswordController::class, 'cambiarPassword']);

    require __DIR__.'/fallback/Usuarios/userFallback.php';

    // Traer los roles
    Route::get('roles', [RolesController::class, 'index'])->name('roles.index');
    Route::patch('/user/{user}', [UserController::class, 'update']);
    Route::delete('user/{user}',[UserController::class, 'destroy']);
    Route::post('/user/secondRegister', [UserController::class, 'store']);
    Route::post('/user/masivRegister', [ImportUsuarioController::class, 'importar']);

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
    // Traer y registrar tipo de control- control
    Route::apiResource('tipo_control', TipoControlController::class);

    Route::post('/tipo_control', [TipoControlController::class, 'store']);
    Route::get('/tipo_control/{id}', [TipoControlController::class, 'show']);
    Route::put('/tipo_control/{id}', [TipoControlController::class, 'update']);
    Route::delete('/tipo_control/{id}', [TipoControlController::class, 'destroy']);
    Route::resource('control', ControlesController::class);

    require __DIR__.'/fallback/Trazabilidad/Control_Tipo_control.php';

    // Lotes
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

    // Bancal
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

    // Actividades
    Route::get('actividades', [ActividadesController::class, 'index'])
        ->name('actividades.index');
    Route::get('actividades/{actividad}', [ActividadesController::class, 'show'])
        ->name('actividades.show');
    Route::post('actividades', [ActividadesController::class, 'store'])
        ->name('actividades.store');
    Route::put('actividades/{actividad}', [ActividadesController::class, 'update'])
        ->name('actividades.update');
    Route::delete('actividades/{actividad}', [ActividadesController::class, 'destroy'])
        ->name('actividades.destroy');
    Route::post('actividades/{actividad}/finalizar', [ActividadesController::class, 'finalizar'])
        ->name('actividades.finalizar');

    // Prestamos Insumos
    Route::get('prestamos-insumos', [PrestamoInsumoController::class, 'index'])
        ->name('prestamos-insumos.index');
    Route::get('prestamos-insumos/{prestamoInsumo}', [PrestamoInsumoController::class, 'show'])
        ->name('prestamos-insumos.show');
    Route::post('prestamos-insumos', [PrestamoInsumoController::class, 'store'])
        ->name('prestamos-insumos.store');
    Route::put('prestamos-insumos/{prestamoInsumo}', [PrestamoInsumoController::class, 'update'])
        ->name('prestamos-insumos.update');
    Route::delete('prestamos-insumos/{prestamoInsumo}', [PrestamoInsumoController::class, 'destroy'])
        ->name('prestamos-insumos.destroy');

    // Prestamos Herramientas
    Route::get('prestamos-herramientas', [PrestamoHerramientaController::class, 'index'])
        ->name('prestamos-herramientas.index');
    Route::get('prestamos-herramientas/{prestamoHerramienta}', [PrestamoHerramientaController::class, 'show'])
        ->name('prestamos-herramientas.show');
    Route::post('prestamos-herramientas', [PrestamoHerramientaController::class, 'store'])
        ->name('prestamos-herramientas.store');
    Route::put('prestamos-herramientas/{prestamoHerramienta}', [PrestamoHerramientaController::class, 'update'])
        ->name('prestamos-herramientas.update');
    Route::delete('prestamos-herramientas/{prestamoHerramienta}', [PrestamoHerramientaController::class, 'destroy'])
        ->name('prestamos-herramientas.destroy');

    // Bodegas
    Route::get('bodegas', [BodegaController::class, 'index'])
        ->name('bodegas.index');
    Route::get('bodegas/{bodega}', [BodegaController::class, 'show'])
        ->name('bodegas.show');
    Route::post('bodegas', [BodegaController::class, 'store'])
        ->name('bodegas.store');
    Route::put('bodegas/{bodega}', [BodegaController::class, 'update'])
        ->name('bodegas.update');
    Route::delete('bodegas/{bodega}', [BodegaController::class, 'destroy'])
        ->name('bodegas.destroy');

    // Tipo Insumos
    Route::get('tipo-insumos', [TipoInsumoController::class, 'index'])
        ->name('tipo-insumos.index');
    Route::get('tipo-insumos/{tipoInsumo}', [TipoInsumoController::class, 'show'])
        ->name('tipo-insumos.show');

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
    Route::delete('cosechas/{cosecha}', [CosechaController::class, 'reportePdf'])
        ->name('cosechas.destroy');
    Route::get('cosechas/reporte/{pdf}', [CosechaController::class, 'reportePdf'])
        ->name('reporte.reportePdf');

    // Tipo residuo y Residuo
    Route::resource('tipo_residuo', TipoResiduoController::class);
    Route::resource('residuo', ResiduoController::class);

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

    // Tipo Plaga
    Route::get('tipo-plaga', [TipoPlagaController::class, 'index'])
        ->name('tipo-plaga.index');
    Route::get('tipo-plaga/{tipoPlaga}', [TipoPlagaController::class, 'show'])
        ->name('tipo-plaga.show');
    Route::post('tipo-plaga', [TipoPlagaController::class, 'store'])
        ->name('tipo-plaga.store');
    Route::put('tipo-plaga/{tipoPlaga}', [TipoPlagaController::class, 'update'])
        ->name('tipo-plaga.update');
    Route::delete('tipo-plaga/{tipoPlaga}', [TipoPlagaController::class, 'destroy'])
        ->name('tipo-plaga.destroy');

    // Plagas
    Route::get('plagas', [PlagaController::class, 'index'])
        ->name('plagas.index');
    Route::get('plagas/{plaga}', [PlagaController::class, 'show'])
        ->name('plagas.show');
    Route::post('plagas', [PlagaController::class, 'store'])
        ->name('plagas.store');
    Route::put('plagas/{plaga}', [PlagaController::class, 'update'])
        ->name('plagas.update');
    Route::delete('plagas/{plaga}', [PlagaController::class, 'destroy'])
        ->name('plagas.destroy');

    // Afecciones
    Route::get('afecciones', [AfeccionController::class, 'index'])
        ->name('afecciones.index');
    Route::get('afecciones/{afeccion}', [AfeccionController::class, 'show'])
        ->name('afecciones.show');
    Route::post('afecciones', [AfeccionController::class, 'store'])
        ->name('afecciones.store');
    Route::patch('afecciones/{afeccion}', [AfeccionController::class, 'update'])
        ->name('afecciones.update');
    Route::post('afecciones/{afeccion}/cambiar_estado', [AfeccionController::class, 'cambiarEstado'])
        ->name('afecciones.cambiar_estado');
    Route::delete('afecciones/{afeccion}', [AfeccionController::class, 'destroy'])
        ->name('afecciones.destroy');

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

    // Pagos
    Route::get('pagos', [PagoController::class, 'index'])
        ->name('pagos.index');
    Route::get('pagos/{pago}', [PagoController::class, 'show'])
        ->name('pagos.show');
    Route::delete('pagos/{pago}', [PagoController::class, 'destroy'])
        ->name('pagos.destroy')->middleware([IsAdmin::class, IsUserAuth::class]);
    Route::post('pagos/calcular', [PagoController::class, 'calcular'])
        ->name('pagos.calcular')->middleware([IsAdmin::class, IsUserAuth::class]);

    // Venta
    Route::resource('venta', VentaController::class)->only(['index', 'store']);
    Route::get('/venta/{venta}/factura_pdf', [VentaController::class, 'facturaPDF']);

    // Tipo Insumos
    Route::post('tipo-insumos', [TipoInsumoController::class, 'store'])
        ->name('tipo-insumos.store');
    Route::put('tipo-insumos/{tipoInsumo}', [TipoInsumoController::class, 'update'])
        ->name('tipo-insumos.update');
    Route::delete('tipo-insumos/{tipoInsumo}', [TipoInsumoController::class, 'destroy'])
        ->name('tipo-insumos.destroy');

    // Insumos
    Route::get('insumos', [InsumoController::class, 'index'])
        ->name('insumos.index');
    Route::post('insumos', [InsumoController::class, 'store'])
        ->name('insumos.store');
    Route::get('insumos/{insumos}', [InsumoController::class, 'show'])
        ->name('insumos.show');
    Route::put('insumos/{insumos}', [InsumoController::class, 'update'])
        ->name('insumos.update');
    Route::delete('insumos/{insumos}', [InsumoController::class, 'destroy'])
        ->name('insumos.destroy');

    // Bodega Insumos
    Route::get('bodega_insumo', [BodegaInsumoController::class, 'index'])
        ->name('bodega_insumo.index');
    Route::post('bodega_insumo', [BodegaInsumoController::class, 'store'])
        ->name('bodega_insumo.store');
    Route::get('bodega_insumo/{bodega_insumo}', [BodegaInsumoController::class, 'show'])
        ->name('bodega_insumo.show');
    Route::put('bodega_insumo/{bodega_insumo}', [BodegaInsumoController::class, 'update'])
        ->name('bodega_insumo.update');
    Route::delete('bodega_insumo/{bodega_insumo}', [BodegaInsumoController::class, 'destroy'])
        ->name('bodega_insumo.destroy');

    // Precio Producto
    Route::get('precio-producto', [PrecioProductoController::class, 'index'])
        ->name('precio-producto.index');
    Route::post('precio-producto', [PrecioProductoController::class, 'store'])
        ->name('precio-producto.store');
    Route::get('precio-producto/{precioProducto}', [PrecioProductoController::class, 'show'])
        ->name('precio-producto.show');
    Route::put('precio-producto/{precioProducto}', [PrecioProductoController::class, 'update'])
        ->name('precio-producto.update');
    Route::delete('precio-producto/{precioProducto}', [PrecioProductoController::class, 'destroy'])
        ->name('precio-producto.destroy');

    // Herramientas
    Route::get('herramientas', [HerramientaController::class, 'index'])
        ->name('herramientas.index');
    Route::get('herramientas/{herramienta}', [HerramientaController::class, 'show'])
        ->name('herramientas.show');
    Route::post('herramientas', [HerramientaController::class, 'store'])
        ->name('herramientas.store');
    Route::put('herramientas/{herramienta}', [HerramientaController::class, 'update'])
        ->name('herramientas.update');
    Route::delete('herramientas/{herramienta}', [HerramientaController::class, 'destroy'])
        ->name('herramientas.destroy');

    // Bodega Herramientas
    Route::get('bodega_herramienta', [BodegaHerramientaController::class, 'index'])
        ->name('bodega_herramienta.index');
    Route::post('bodega_herramienta', [BodegaHerramientaController::class, 'store'])
        ->name('bodega_herramienta.store');
    Route::get('bodega_herramienta/{bodegaHerramienta}', [BodegaHerramientaController::class, 'show'])
        ->name('bodega_herramienta.show');
    Route::put('bodega_herramienta/{bodegaHerramienta}', [BodegaHerramientaController::class, 'update'])
        ->name('bodega_herramienta.update');
    Route::delete('bodega_herramienta/{bodegaHerramienta}', [BodegaHerramientaController::class, 'destroy'])
        ->name('bodega_herramienta.destroy');

    // IoT Routes
    Route::resource('tipo_sensores', TipoSensorController::class);
    Route::resource('sensors', SensorController::class);
    Route::resource('dato_meteorologicos', DatoMeteorologicoController::class);
    Route::resource('dato_historicos', DatoHistoricoController::class);

    // ── Subgrupo: sólo administradores pueden modificar insumos y tipos de actividad ────────────
    Route::middleware(IsAdmin::class)->group(function () {
        // Tipos de actividad
        //Route::post('tipo-actividades', [TipoActividadController::class, 'store'])
        //    ->name('tipo-actividades.store');
        //Route::put('tipo-actividades/{tipoActividad}', [TipoActividadController::class, 'update'])
        //    ->name('tipo-actividades.update');
        //Route::delete('tipo-actividades/{tipoActividad}', [TipoActividadController::class, 'destroy'])
        //    ->name('tipo-actividades.destroy');
    });
});