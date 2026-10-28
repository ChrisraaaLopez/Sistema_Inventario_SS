<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ArticuloController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\ModeloController;
use App\Http\Controllers\PuestoController;
use App\Http\Controllers\TipoController;
use App\Http\Controllers\UbicacionController;
use App\Http\Controllers\RevisionController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\VehiculoController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'autenticar'])->middleware('throttle:10,1')->name('login.authenticate');
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Consulta puede consultar los catálogos; solo Administrador puede modificar.
    Route::middleware('rol:Administrador,Consulta')->group(function () {
        Route::get('/articulos', [ArticuloController::class, 'index'])->name('articulos.index');
        Route::get('/articulos/{articulo}', [ArticuloController::class, 'show'])->whereNumber('articulo')->name('articulos.show');
        Route::get('/empleados', [EmpleadoController::class, 'index'])->name('empleados.index');
        Route::get('/facturas', [FacturaController::class, 'index'])->name('facturas.index');
        Route::get('/facturas/{factura}', [FacturaController::class, 'show'])->whereNumber('factura')->name('facturas.show');
        Route::get('/archivos/facturas/{factura}', [FacturaController::class, 'archivo'])->whereNumber('factura')->name('facturas.archivo');
        Route::get('/archivos/articulos/{articulo}/imagen', [ArticuloController::class, 'imagen'])->whereNumber('articulo')->name('articulos.imagen');
        Route::get('/marcas', [MarcaController::class, 'index'])->name('marcas.index');
        Route::get('/modelos', [ModeloController::class, 'index'])->name('modelos.index');
        Route::get('/puestos', [PuestoController::class, 'index'])->name('puestos.index');
        Route::get('/tipos', [TipoController::class, 'index'])->name('tipos.index');
        Route::get('/ubicaciones', [UbicacionController::class, 'index'])->name('ubicaciones.index');
    });

    Route::middleware('rol:Administrador')->group(function () {
        Route::get('/articulos/generar-codigo', [ArticuloController::class, 'generarCodigo'])->name('articulos.generar-codigo');
        Route::post('/marcas/rapida', [MarcaController::class, 'quickStore'])->name('marcas.quick-store');
        Route::post('/modelos/rapido', [ModeloController::class, 'quickStore'])->name('modelos.quick-store');
        Route::post('/facturas/rapida', [FacturaController::class, 'quickStore'])->name('facturas.quick-store');
        Route::resource('usuarios', UsuarioController::class)->except(['show']);
        Route::resource('vehiculos', VehiculoController::class)->except(['show']);
        Route::resource('articulos', ArticuloController::class)->except(['index', 'show']);
        Route::resource('empleados', EmpleadoController::class)->except(['index', 'show']);
        Route::resource('facturas', FacturaController::class)->except(['index', 'show']);
        Route::resource('marcas', MarcaController::class)->except(['index', 'show']);
        Route::resource('modelos', ModeloController::class)->except(['index', 'show']);
        Route::resource('puestos', PuestoController::class)->except(['index', 'show']);
        Route::resource('tipos', TipoController::class)->except(['index', 'show']);
        Route::resource('ubicaciones', UbicacionController::class)
    ->except(['index', 'show'])
    ->parameters(['ubicaciones' => 'ubicacion']);
    });

    // Administrador y Revisor operan revisiones; Consulta solo ve histórico e impresión.
    Route::prefix('revision')->name('revision.')->group(function () {
        Route::middleware('rol:Administrador,Revisor')->group(function () {
            Route::get('/periodos', [RevisionController::class, 'periodos'])->name('periodos');
            Route::get('/resguardos/{resguardo}/escanear', [RevisionController::class, 'scan'])->name('scan');
            Route::post('/resguardos/{resguardo}/buscar', [RevisionController::class, 'buscar'])->name('scan.lookup');
            Route::post('/resguardos/{resguardo}/reasignar/{articulo}', [RevisionController::class, 'reasignar'])->middleware('rol:Administrador,Revisor')->name('reassign');
            Route::get('/resguardos/{resguardo}/cierre', [RevisionController::class, 'cierre'])->name('close');
            Route::post('/resguardos/{resguardo}/cerrar', [RevisionController::class, 'cerrarResguardo'])->name('close.store');
        });
        Route::middleware('rol:Administrador,Revisor')->group(function () {
            Route::get('/periodos/{periodo}/seleccionar', [RevisionController::class, 'seleccionar'])->name('select');
            Route::post('/periodos/{periodo}/iniciar', [RevisionController::class, 'iniciar'])->name('start');
        });
        Route::middleware('rol:Administrador')->group(function () {
            Route::get('/periodos/nuevo', [RevisionController::class, 'crearPeriodo'])->name('periodos.create');
            Route::post('/periodos', [RevisionController::class, 'guardarPeriodo'])->name('periodos.store');
            Route::post('/periodos/{periodo}/cerrar', [RevisionController::class, 'cerrarPeriodo'])->name('period.close');
            Route::post('/periodos/{periodo}/reabrir', [RevisionController::class, 'reabrirPeriodo'])->name('period.reopen');
            Route::post('/resguardos/{resguardo}/reabrir', [RevisionController::class, 'reabrirResguardo'])->name('resguardo.reopen');
        });
    });

    Route::get('/modelos-por-marca/{marca}', [ModeloController::class, 'porMarca'])->middleware('rol:Administrador,Consulta')->whereNumber('marca')->name('modelos.por-marca');

    Route::get('/revision/historico', [RevisionController::class, 'historico'])->middleware('rol:Administrador,Revisor,Consulta')->name('revision.history');

    Route::get('/resguardos/{resguardo}/imprimir', [RevisionController::class, 'imprimir'])
        ->middleware('rol:Administrador,Consulta,Revisor')->name('resguardo.print');
});
