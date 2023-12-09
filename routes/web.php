<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    CarritoController,
    CarritoInventarioController,
    CategoriaController,
    ClienteController,
    CobrarController,
    UserController,
    DashboardController,
    FacturaController,
    InventarioController,
    LoginController,
    MarcaController,
    PagarController,
    PoController,
    ProductoController,
    ProveedoreController,
    ReporteController,
    UtilidadeController,
};


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/** LOGIN */
Route::get('/', function () {
    return redirect('/login');
})->middleware('guest');

Route::get('/login', [LoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'store'])->name('login.store');
Route::post('/logout', [LoginController::class, 'logout'])->name('login.logout');
/** CIERRE LOGIN */



Route::middleware(['auth'])->group(function () {
    /** RUTAS DE PANEL */
        Route::get('/panel', [DashboardController::class, 'index'])->name('admin.panel.index');

    /** ADMINISTRADOR */
        /** RUTAS DE FACTURAS */
        Route::get('/facturas/{id}', [FacturaController::class, "show"])->name('admin.factura.ver');
        Route::resource('/cobrar', CobrarController::class)->names('admin.cuentas.por.cobrar');
        Route::resource('/pagar', PagarController::class)->names('admin.cuentas.por.pagar');
        
        /** RUTAS DE USUARIOS */
        Route::resource('/usuarios', UserController::class)->names('admin.users');
        
        /** RUTA EDITAR TASA */
        Route::put('/updateTasa/{idTasa}', [UtilidadeController::class, 'updateTasa'])->name('admin.utilidades.updateTasa');
        
        /** RUTAS DE UTILIDADES */
        Route::resource('/utilidades', UtilidadeController::class)->names('admin.utilidades');
    
        /** RUTAS DE INVENTARIO */
        Route::get('/inventarios/listaEntradas', [InventarioController::class, "listaEntradas"])->name('admin.inventarios.listaEntradas');
        Route::get('/inventarios/listaSalidas', [InventarioController::class, "listaSalidas"])->name('admin.inventarios.listaSalidas');
        Route::get('/inventarios/crearEntrada', [InventarioController::class, "crearEntrada"])->name('admin.inventarios.crearEntrada');
        Route::get('/inventarios/crearSalida', [InventarioController::class, "crearSalida"])->name('admin.inventarios.crearSalida');
        Route::resource('/inventarios', InventarioController::class)->names('admin.inventarios');
        
        /** Rutas de productos */
        Route::get('/productos/categorias', [CategoriaController::class, 'index'] )->name('admin.productos.categorias');
        Route::get('/productos/marcas', [MarcaController::class, "index" ])->name('admin.productos.marcas');
        Route::get('/formularioEditarProducto/{codigo}', [ProductoController::class, "formularioEditarProducto" ])->name('admin.formularioEditarProducto');
        Route::resource('/productos', ProductoController::class)->names('admin.productos');
    
        /** Rutas de proveedores */
        Route::resource('/proveedores', ProveedoreController::class)->names('admin.proveedores');
        
        /** Rutas de Categorias */
        Route::prefix('productos')->group(function (){
            Route::resource('/categorias', CategoriaController::class)->names('admin.categorias');
            Route::resource('/marcas', MarcaController::class)->names('admin.marcas');
        });
        
        /** REPORTES */
        Route::get('/reportes', [ReporteController::class, "index"])->name('admin.reportes.index');

    /** @param CIERRE ADMINISTRADOR */
    
    /** VENDEDOR - ADMIN */
        /** LISTA INVENTARIO  */
        Route::prefix('vendedor')->group(function (){
            Route::get('/inventarios', [InventarioController::class, "getInventarioVendedor"])->name('vendedor.inventarios');
            Route::get('/reportes', [ReporteController::class, "index"])->name('vendedor.reportes');
        });

        /** POS VENTA */
        // Route::prefix('pos')->group(function (){
            Route::get('imprimirFactura/{codigoFactura}', [FacturaController::class, 'imprimirFactura'])->name('admin.imprimirFactura');
            Route::resource('facturas', FacturaController::class)->names('admin.facturas');
            Route::resource('clientes', ClienteController::class)->names('admin.clientes');
        // });
        Route::resource('pos', PoController::class)->names('admin.pos');
      
});