<?php

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    UsuarioController,
    ApiController,
    CarritoController,
    CarritoInventarioController,
    CategoriaController,
    ClienteController,
    FacturaController,
    FacturaInventarioController,
    InventarioController,
    LoginController,
    MarcaController,
    PoController,
    ProductoController,
    ProveedoreController,
    ReporteController
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

    /** AUTORIZACION */
    Route::post('verificarClave', [LoginController::class, 'verificarClave'])->name('api.verificarClave');

    /** EMPRESA */
    Route::get('getEmpresa', [PoController::class, 'getEmpresa'])->name('api.empresa');

    /** REPORTES */
    Route::post('storeReportes', [ReporteController::class, 'storeReportes'])->name('storeReportes');

    /** PRODUCTOS */
    Route::post('getProductosFiltro', [ProductoController::class, 'getProductosFiltro'])->name('api.getProductosFiltro');
    Route::get('getProductoData/{barcode}', [ProductoController::class, 'getProductoData'])->name('api.getProductoData');
    Route::get('getProducto/{barcode}', [ProductoController::class, 'getProducto'])->name('api.getProducto');
    Route::get('getProductos', [ProductoController::class, 'getProductos'])->name('api.getProductos');
    Route::resource('productos', ProductoController::class)->names('api.productos');
    
    /** CATEGORIAS */
    Route::get('getCategorias', [CategoriaController::class, 'getCategorias'])->name('api.getCategorias');
    
    /** MARCAS */
    Route::get('getMarcas', [MarcaController::class, 'getMarcas'])->name('api.getMarcas');
    
    /** INVENTARIOS */
    Route::get('getInventarios', [InventarioController::class, 'getInventarios'])->name('api.getInventarios');
    Route::post('getInventariosFiltro', [InventarioController::class, 'getInventariosFiltro'])->name('api.getInventariosFiltro');
    Route::delete('deleteProductoDelInventario/{id}', [InventarioController::class, 'deleteProductoDelInventario'])->name('api.deleteProductoDelInventario');
    Route::put('editarProductoDelInventario/{id}', [InventarioController::class, 'editarProductoDelInventario'])->name('api.editarProductoDelInventario');
    
    /** FACTURAS DE INVENTARIO ENTRADA*/
    Route::post('facturarCarritoEntrada', [CarritoInventarioController::class, 'facturarCarritoEntrada'])->name('admin.facturarCarritoEntrada');
    Route::post('setFacturaEntrada', [FacturaInventarioController::class, 'setFacturaEntrada'])->name('admin.setFacturaEntrada');
    Route::post('getFacturaES', [FacturaInventarioController::class, 'getFacturaES'])->name('admin.getFacturaES');
    
    /** FACTURAS DE INVENTARIO SALIDAS*/
    Route::post('facturarCarritoSalida', [CarritoInventarioController::class, 'facturarCarritoSalida'])->name('admin.facturarCarritoSalida');
    Route::post('setFacturaSalida', [FacturaInventarioController::class, 'setFacturaSalida'])->name('admin.setFacturaSalida');

    
    /** FACTURAS */
    Route::get('getCarrito/{codigoFactura}', [CarritoController::class, 'getCarrito'])->name('admin.getCarrito');
    Route::post('facturarCarrito', [CarritoController::class, 'facturarCarrito'])->name('admin.facturarCarrito');
    Route::get('getFactura/{codigoFactura}', [FacturaController::class, 'getFactura'])->name('admin.getFactura');
    Route::get('getCodigoFactura/{tabla}', [FacturaController::class, 'getCodigoFactura'])->name('admin.getCodigoFactura');
    Route::resource('facturas', FacturaController::class)->names('api.facturas');
    
    /** FACTURAS INVENTARIO */
    Route::post('storeSalida', [FacturaInventarioController::class, 'storeSalida'])->name('api.facturasInventarios.storeSalida');
    Route::resource('facturasInventarios', FacturaInventarioController::class)->names('api.facturasInventarios');
    
    /** CLIENTES */
    Route::get('getCliente/{idCliente}', [ClienteController::class, 'getCliente'])->name('api.getCliente');
    Route::post('storeCliente', [ClienteController::class, 'storeCliente'])->name('api.storeCliente');
    Route::put('updateCliente/{id}', [ClienteController::class, 'updateCliente'])->name('api.updateCliente');
    
    /** PROVEEDORES */
    Route::get('getProveedor/{idProveedor}', [ProveedoreController::class, 'getProveedor'])->name('api.getProveedor');
    Route::post('storeProveedor', [ProveedoreController::class, 'storeProveedor'])->name('api.storeProveedor');
    Route::put('updateProveedor/{id}', [ProveedoreController::class, 'updateProveedor'])->name('api.updateProveedor');
});
