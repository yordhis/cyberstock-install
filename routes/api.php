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
    /** PRODUCTOS */
    Route::get('getProductoData/{barcode}', [ProductoController::class, 'getProductoData'])->name('api.productos');
    Route::get('getProducto/{barcode}', [ProductoController::class, 'getProducto'])->name('api.productos');
    Route::get('getProductos', [ProductoController::class, 'getProductos'])->name('api.productos');
    Route::resource('productos', ProductoController::class)->names('api.productos');
   
    /** INVENTARIOS */
    Route::get('getInventarios', [InventarioController::class, 'getInventarios'])->name('api.getInventarios');
    Route::post('getInventariosFiltro', [InventarioController::class, 'getInventariosFiltro'])->name('api.getInventariosFiltro');
    Route::delete('deleteProductoDelInventario/{id}', [InventarioController::class, 'deleteProductoDelInventario'])->name('api.deleteProductoDelInventario');
    Route::put('editarProductoDelInventario/{id}', [InventarioController::class, 'editarProductoDelInventario'])->name('api.editarProductoDelInventario');
    
    /** FACTURAS */
    Route::post('imprimirFactura/{codigoFactura}', [FacturaController::class, 'imprimirFactura'])->name('admin.imprimirFactura');
    Route::get('getCodigoFactura', [FacturaController::class, 'getCodigoFactura'])->name('admin.getCodigoFactura');
    Route::resource('facturas', FacturaController::class)->names('api.facturas');
    
    /** FACTURAS INVENTARIO */
    Route::post('storeSalida', [FacturaInventarioController::class, 'storeSalida'])->name('api.facturasInventarios.storeSalida');
    Route::resource('facturasInventarios', FacturaInventarioController::class)->names('api.facturasInventarios');
    
    /** CLIENTES */
    Route::get('getCliente/{idCliente}', [ClienteController::class, 'getCliente'])->name('api.getCliente');
    Route::post('storeCliente', [ClienteController::class, 'storeCliente'])->name('api.storeCliente');
    Route::put('updateCliente/{id}', [ClienteController::class, 'updateCliente'])->name('api.updateCliente');
    
    /** PROVEEDORES */
    Route::get('getProveedor/{idProveedor}', [ApiController::class, 'getProveedor'])->name('api.getProveedor');
});
