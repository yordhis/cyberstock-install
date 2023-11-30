<?php

namespace App\Http\Controllers;

use App\Models\Carrito;
use App\Http\Requests\StoreCarritoRequest;
use App\Http\Requests\UpdateCarritoRequest;
use App\Models\Cliente;
use App\Models\DataDev;
use App\Models\Helpers;
use App\Models\Inventario;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Request;

class CarritoController extends Controller
{
    public $data;

    public function __construct()
    {
        $this->data = new DataDev;
    }

    /** @ API CARRITO @ */
        /** Listar carrito */
        public function getCarrito($codigoFactura)
        {
            try {
                $carrito = Carrito::where('codigo', $codigoFactura)->get();
                if (count($carrito)) {
                    return response()->json([
                        "mensaje" => "Consulta exitosa del carrito",
                        "data" => $carrito,
                        "estatus" => Response::HTTP_OK
                    ], Response::HTTP_OK);
                } else {
                    return response()->json([
                        "mensaje" => "No hay carrito facturado con el codigo de factura ingresado",
                        "data" => $carrito,
                        "estatus" => Response::HTTP_OK
                    ], Response::HTTP_OK);
                }
            } catch (\Throwable $th) {
                $mensaje = Helpers::getMensajeError($th, "Error al consultar carrito de compra");
                return response()->json([
                    "mensaje" => $mensaje,
                    "data" => [],
                    "estatus" => Response::HTTP_INTERNAL_SERVER_ERROR
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        /** Registrar producto en el carrito */
        public function facturarCarrito(HttpRequest $request)
        {
            try {
        
                /** Validadmos que la cantida no sobre pase la del inventario */
                // $productoEnInventario = Inventario::where('codigo', $request->codigo_producto)->get();
                // if (count($productoEnInventario)) {
                //     if ($request->cantidad > $productoEnInventario[0]->cantidad) {
                //         return response()->json([
                //             "mensaje" => "La Existencia es insuficiente para facturar el producto {$request->descripcion} | Disponibles: {$request->cantidad} .",
                //             "data" => $productoEnInventario,
                //             "estatus" => Response::HTTP_UNAUTHORIZED
                //         ], Response::HTTP_UNAUTHORIZED);
                //     }
                // }

                /** Registramos el producto en el carrito DB */
                $resultado = Carrito::create($request->all());

                if($resultado){
                    return response()->json([
                        "mensaje" => "El producto se agrego al carrito de la factura correctamente",
                        "data" => $resultado,
                        "estatus" => Response::HTTP_OK,
                    ], Response::HTTP_OK);
                }else{
                    return response()->json([
                        "mensaje" => "El producto no se agrego al carrito de la factura.",
                        "data" => $resultado,
                        "estatus" => Response::HTTP_NOT_FOUND,
                    ], Response::HTTP_NOT_FOUND);
                }

            } catch (\Throwable $th) {
                $mensaje = Helpers::getMensajeError($th, "Error al intentar agregar producto al carrito de la factura, ");
                return response()->json([
                    "mensaje" => $mensaje,
                    "data" =>  $request->request,
                    "estatus" => Response::HTTP_INTERNAL_SERVER_ERROR
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        /** Editar el producto del carrito */
        /** Eliminar producto del carritos */
        public function eliminarProductoCarrito($idProductoCarrito)
        {
            try {
                
                if($idProductoCarrito > 0) $resultado = Carrito::where('id', $idProductoCarrito)->delete();

                if($resultado){
                    return response()->json([
                        "mensaje" => "El producto se eliminó del carrito de compra.",
                        "data" => $resultado,
                        "estatus" => Response::HTTP_OK,
                    ], Response::HTTP_OK);
                }else{
                    return response()->json([
                        "mensaje" => "¡El producto NO se eliminó del carrito de compra!",
                        "data" => $resultado,
                        "estatus" => Response::HTTP_NOT_FOUND,
                    ], Response::HTTP_OK);
                }
                
            } catch (\Throwable $th) {
                $mensajeError = Helpers::getMensajeError($th, "Error Al eliminar la producto del carrito, ");
                return response()->json([
                    "mensaje" => $mensajeError,
                    "data" => [],
                    "estatus" => Response::HTTP_INTERNAL_SERVER_ERROR,
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        /** Eliminar todo el carrito de la factura */
        public function eliminarCarritoCompleto($codigoFactura)
        {
            try {
            
                $estatusEliminar = Carrito::where('codigo', $codigoFactura)->delete();
                if($estatusEliminar){
                    $mensaje = $estatusEliminar ? "Todos los productos del carrito se eliminarón correctamente de la factura" : "No se pudo eliminar el carrito de la factura";
                    $estatus = $estatusEliminar ? Response::HTTP_OK : Response::HTTP_NOT_FOUND;
                } else {
                    $mensaje = "La factura no poseé carrito de compra.";
                    $estatus = Response::HTTP_NOT_FOUND;
                }

                return response()->json([
                    "mensaje" => $mensaje,
                    "data" => $estatusEliminar,
                    "estatus" => $estatus,
                ], $estatus);

            } catch (\Throwable $th) {
                $mensajeError = Helpers::getMensajeError($th, "Error Al Eliminar todos los productos de la factura, ");
                return response()->json([
                    "mensaje" =>  $mensajeError,
                    "data" => [],
                    "estatus" => Response::HTTP_INTERNAL_SERVER_ERROR,
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

    /** @ CIERRE API CARRITO @ */
  
}
