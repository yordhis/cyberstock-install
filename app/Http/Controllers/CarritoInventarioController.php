<?php

namespace App\Http\Controllers;

use App\Models\CarritoInventario;
use App\Http\Requests\StoreCarritoInventarioRequest;
use App\Http\Requests\UpdateCarritoInventarioRequest;
use App\Models\Carrito;
use App\Models\DataDev;
use App\Models\FacturaInventario;
use App\Models\Helpers;
use App\Models\Inventario;
use App\Models\Proveedore;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Request;

class CarritoInventarioController extends Controller
{

    public $data;

    public function __construct()
    {
        $this->data = new DataDev;
    }
   

    /** API REST FULL*/
    public function facturarCarritoEntrada(HttpRequest $request)
    {
        try {
            // Añadimos al carrito el producto
            /** Registramos el producto en el carrito DB */
                $resultado = CarritoInventario::create($request->all());
                return response()->json([
                    "mensaje" => "El carrito se facturó correctamente",
                    "data" => $resultado,
                    "estatus" => Response::HTTP_OK,
                ], Response::HTTP_OK);
   
        } catch (\Throwable $th) {
               $mensaje = Helpers::getMensajeError($th, "Error al intentar registrar factura, ");
               return response()->json([
                   "mensaje" => $mensaje,
                   "data" =>  $request->request,
                   "estatus" => Response::HTTP_INTERNAL_SERVER_ERROR
               ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
           

    }

    public function facturarCarritoSalida(HttpRequest $request)
    {
        try {
        
                /** Creamos el carrito de la factura en caso de que sea 
                 * @param VENTA
                 * @param CREDITO
                 */
                Carrito::create([
                    "codigo" => $request->codigo_factura, 
                    "codigo_producto" => $request->codigo_producto,
                    "identificacion"  => $request->identificacion,
                    "cantidad"  => $request->cantidad,
                    "costo"  => $request->costo,
                    "subtotal"  => $request->subtotal,
                    "descripcion"  => $request->descripcion
                ]);
                $estatusCrear = CarritoInventario::create($request->all());

                if($estatusCrear){
                    return response()->json([
                        "mensaje" => "Producto del carrito facturado correctamente",
                        "data" => $estatusCrear,
                        "estatus" =>  Response::HTTP_CREATED
                    ], Response::HTTP_CREATED);
                }else {
                    return response()->json([
                        "mensaje" => "Producto del carrito NO se facturó.",
                        "data" => $estatusCrear,
                        "estatus" =>  Response::HTTP_NOT_FOUND
                    ], Response::HTTP_NOT_FOUND);
                }

        } catch (\Throwable $th) {
            $mensajeError = Helpers::getMensajeError($th, "Error Al crear la carrito de salida, ");
            return response()->json([
                "mensaje" => $mensajeError,
                "data" => [],
                "estatus" =>  Response::HTTP_INTERNAL_SERVER_ERROR
            ], 
            Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

   
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CarritoInventario  $carritoInventario
     * @return \Illuminate\Http\Response
     */
    public function destroySalida($codigoFactura, $codigoProducto)
    {
        try {
            $identificacion = 0;
            // Validadr si existe en el caritto de inventario
            $extisteCarritoInventario = CarritoInventario::where([
                'codigo' => $codigoFactura,
                'codigo_producto' => $codigoProducto
            ])->get();

            
            if (count($extisteCarritoInventario)) {
                $identificacion =  $extisteCarritoInventario[0]->identificacion;
                CarritoInventario::where([
                    'codigo' => $codigoFactura,
                    'codigo_producto' => $codigoProducto
                ])->delete();
            }

            // Validadr si existe en el caritto de factura
            $extisteCarritoFactura = Carrito::where([
                'codigo' => $codigoFactura,
                'codigo_producto' => $codigoProducto
            ])->get();

            if (count($extisteCarritoFactura)) {
                Carrito::where([
                    'codigo' => $codigoFactura,
                    'codigo_producto' => $codigoProducto
                ])->delete();
            }
           
            $menuSuperior = $this->data->menuSuperior;
        
            return redirect()->route('admin.inventarios.crearSalida', compact('identificacion'));
        } catch (\Throwable $th) {
            $mensajeError = Helpers::getMensajeError($th, "Error Al eliminar la producto del carrito, ");
            return view('errors.404', compact('mensajeError'));
        }
    }
    
    public function destroy($codigoFactura, $codigoProducto)
    {
        try {
            $identificacion = 0;
            // Validadr si existe en el caritto de inventario
            $extisteCarritoInventario = CarritoInventario::where([
                'codigo' => $codigoFactura,
                'codigo_producto' => $codigoProducto
            ])->get();

            
            if (count($extisteCarritoInventario)) {
                $identificacion =  $extisteCarritoInventario[0]->identificacion;
                CarritoInventario::where([
                    'codigo' => $codigoFactura,
                    'codigo_producto' => $codigoProducto
                ])->delete();
            }

            // Validadr si existe en el caritto de factura
            $extisteCarritoFactura = Carrito::where([
                'codigo' => $codigoFactura,
                'codigo_producto' => $codigoProducto
            ])->get();

            if (count($extisteCarritoFactura)) {
                Carrito::where([
                    'codigo' => $codigoFactura,
                    'codigo_producto' => $codigoProducto
                ])->delete();
            }
           
            $menuSuperior = $this->data->menuSuperior;
        
            return redirect()->route('admin.inventarios.crearEntrada', compact('identificacion'));
        } catch (\Throwable $th) {
            $mensajeError = Helpers::getMensajeError($th, "Error Al eliminar la producto del carrito, ");
            return view('errors.404', compact('mensajeError'));
        }
    }

    /**
     * Elimina todo el carrito de compra
     * @param codigo
     */
    public function eliminarCarritoInventarioCompletoSalida($codigo){
        try {
            $carritoExiste = CarritoInventario::where('codigo', $codigo)->get();
            if(count($carritoExiste)){
                $estatusEliminar = CarritoInventario::where('codigo', $codigo)->delete();
                // Obtenemos los products del carrito POS
                $carritoFacturaExiste = Carrito::where('codigo', $codigo)->get();
                if(count($carritoFacturaExiste)){
                    $estatusEliminar = Carrito::where('codigo', $codigo)->delete();
                }
                $mensaje = $estatusEliminar ? "La Factura se eliminó correctamente." : "No se pudo eliminar la factura";
                $estatus = $estatusEliminar ? 201 : 404;
            }else{
                $mensaje = "La factura no se ha registrado, no se encontro factura que eliminar.";
                $estatus = 404;
            }
      
            return redirect()->route('admin.inventarios.crearSalida', compact('mensaje', 'estatus') );
    
        } catch (\Throwable $th) {
            $mensajeError = Helpers::getMensajeError($th, "Error Al Elimianr factura de entrada, ");
            return view('errors.404', compact('mensajeError'));
        }
    }

    public function eliminarCarritoInventarioCompleto($codigo){
        try {
            $carritoExiste = CarritoInventario::where('codigo', $codigo)->get();
            if(count($carritoExiste)){
                $estatusEliminar = CarritoInventario::where('codigo', $codigo)->delete();
                $mensaje = $estatusEliminar ? "La Factura se eliminó correctamente." : "No se pudo eliminar la factura";
                $estatus = $estatusEliminar ? 201 : 404;
            }else{
                $mensaje = "La factura no se ha registrado, no se encontro factura que eliminar.";
                $estatus = 404;
            }
      
            return redirect()->route('admin.inventarios.crearEntrada', compact('mensaje', 'estatus') );
    
        } catch (\Throwable $th) {
            $mensajeError = Helpers::getMensajeError($th, "Error Al Elimianr factura de entrada, ");
            return view('errors.404', compact('mensajeError'));
        }
    }



}
