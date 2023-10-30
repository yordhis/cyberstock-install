<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    CarritoController,
    CarritoInventarioController,
    CategoriaController,
    ClienteController,
    UserController,
    DashboardController,
    FacturaController,
    InventarioController,
    LoginController,
    MarcaController,
    PoController,
    ProductoController,
    ProveedoreController,
    UtilidadeController,
    UtilityController,
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

Route::get('/', function () {
    return redirect('/login');
})->middleware('guest');

/**
 * Rutas de Profesor
 */
Route::get('/login', [LoginController::class, 'index'])->name('login.index')->middleware('guest');
Route::post('/login', [LoginController::class, 'store'])->name('login.store');
Route::post('/logout', [LoginController::class, 'logout'])->name('login.logout');




Route::middleware(['auth'])->group(function () {
    // Panel Principal
    Route::get('/panel', [DashboardController::class, 'index']);
    
    //  Usuarios
    Route::resource('/usuarios', UserController::class)->names('admin.users');
    Route::put('/updateTasa/{idTasa}', [UtilidadeController::class, 'updateTasa'])->name('admin.utilidades.updateTasa');
    Route::resource('/utilidades', UtilidadeController::class)->names('admin.utilidades');
  
    //  Inventario
    Route::get('/inventarios/general', [InventarioController::class, "index"])->name('admin.inventarios.general');
    Route::get('/inventarios/listaEntradas', [InventarioController::class, "listaEntradas"])->name('admin.inventarios.listaEntradas');
    Route::get('/inventarios/listaSalidas', [InventarioController::class, "listaSalidas"])->name('admin.inventarios.listaSalidas');
    Route::get('/inventarios/crearEntrada', [InventarioController::class, "crearEntrada"])->name('admin.inventarios.crearEntrada');
    Route::get('/inventarios/crearSalida', [InventarioController::class, "crearSalida"])->name('admin.inventarios.crearSalida');
    Route::post('/storeSalida', [CarritoInventarioController::class, "storeSalida"])->name('admin.carrito.inventario.salida');
    Route::post('/carritoInventario', [CarritoInventarioController::class, "store"])->name('admin.carrito.inventario');
    Route::resource('/inventarios', InventarioController::class)->names('admin.inventarios');
    
    /** Rutas de productos */
    Route::get('/productos/categorias', [CategoriaController::class, 'index'] )->name('admin.productos.categorias');
    Route::get('/productos/marcas', [MarcaController::class, "index" ])->name('admin.productos.marcas');
    Route::resource('/productos', ProductoController::class)->names('admin.productos');
 
    /** Rutas de proveedores */
    Route::resource('/proveedores', ProveedoreController::class)->names('admin.proveedores');
    
    /** Rutas de Categorias */
    Route::prefix('productos')->group(function (){
        Route::resource('/categorias', CategoriaController::class)->names('admin.categorias');
        Route::resource('/marcas', MarcaController::class)->names('admin.marcas');
    });
    
    /** POS */
    Route::prefix('pos')->group(function (){
        Route::get('imprimirFactura/{codigoFactura}', [FacturaController::class, 'imprimirFactura'])->name('admin.imprimirFactura');
        Route::resource('facturas', FacturaController::class)->names('admin.facturas');
        Route::resource('clientes', ClienteController::class)->names('admin.clientes');
    });
    Route::resource('pos', PoController::class)->names('admin.pos');
    
    /** Carrito */
    Route::delete('/eliminarCarritoCompleto/{codigo}', [CarritoController::class, 'eliminarCarritoCompleto'])->name('admin.eliminarCarritoCompleto');
    Route::resource('/carritos', CarritoController::class)->names('admin.carritos');
  

    // Route::get('/generarReciboDePago/{id}/recibopdf', [PagoController::class, 'recibopdf'])->name('admin.pagos.recibopdf');
    
    
});