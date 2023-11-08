<?php

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    UsuarioController,
    ApiController,
    ClienteController,
    FacturaController,
    FacturaInventarioController,
    InventarioController,
    ProductoController,
    ProveedoreController
};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::resource('categorias', CategoriaController::class)->names('api.categorias');
// Route::resource('marcas', MarcasController::class)->names('api.marcas');
Route::group(['middleware' => ['cors']], function () {
    //Rutas a las que se permitirá acceso
    Route::get('getProductoData/{barcode}', [ProductoController::class, 'getProductoData'])->name('api.productos');
    Route::get('getProducto/{barcode}', [ProductoController::class, 'getProducto'])->name('api.productos');
    Route::get('getProductos', [ProductoController::class, 'getProductos'])->name('api.productos');
    Route::resource('productos', ProductoController::class)->names('api.productos');
    Route::resource('inventarios', InventarioController::class)->names('api.inventarios');
    
    Route::post('imprimirFactura/{codigoFactura}', [FacturaController::class, 'imprimirFactura'])->name('admin.imprimirFactura');
    Route::resource('facturas', FacturaController::class)->names('api.facturas');
    Route::post('storeSalida', [FacturaInventarioController::class, 'storeSalida'])->name('api.facturasInventarios.storeSalida');
    Route::resource('facturasInventarios', FacturaInventarioController::class)->names('api.facturasInventarios');
    
    Route::get('getCliente/{idCliente}', [ClienteController::class, 'getCliente'])->name('api.clientes');
    Route::get('getProveedor/{idProveedor}', [ApiController::class, 'getProveedor'])->name('api.getProveedor');
});
