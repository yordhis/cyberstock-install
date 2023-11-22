<?php

namespace App\Http\Controllers;

use App\Models\FacturaInventario;
use App\Http\Requests\StoreFacturaInventarioRequest;
use App\Http\Requests\UpdateFacturaInventarioRequest;
use App\Models\CarritoInventario;
use App\Models\Cliente;
use App\Models\Factura;
use App\Models\Helpers;
use App\Models\Inventario;
use App\Models\Po;
use App\Models\Producto;
use App\Models\Proveedore;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FacturaInventarioController extends Controller
{
    /** API REST FULL */
    // Aqui se Factura  y procesan las entradas de los producto del inventario
    public function setFacturaEntrada(Request $request)
    {
        try {
            
            // $carrito = CarritoInventario::where('codigo', $request->codigo)->get();
            // if(count($carrito)){
            //     $resultado = FacturaInventario::create($request->all());
            //     $mensaje = $resultado ? "Se proceso la compra correctamente correctamente." : "No se registro la factura de compra";
            //     $estatus = $resultado ? Response::HTTP_OK : Response::HTTP_NOT_FOUND;
            // }else{
            //     return response()->json([
            //         "mensaje" => "La factura no puede ser procesa porque no poseé productos facturados",
            //         "data" =>  [], 
            //         "estatus" =>Response::HTTP_NOT_FOUND 
            //     ],Response::HTTP_NOT_FOUND);
            // }

            $resultado = FacturaInventario::create($request->all());
            $mensaje = $resultado ? "Se proceso la compra correctamente correctamente." : "No se registro la factura de compra";
            $estatus = $resultado ? Response::HTTP_OK : Response::HTTP_NOT_FOUND;
            
            if($resultado){
                // Procedemos a descontar del inventario
                $carritos = CarritoInventario::where("codigo", $request->codigo)->get();
                $totalArticulos = 0;

                foreach ($carritos as $key => $producto) {
                    // obtenemos los datos del producto
                    $dataProducto = Producto::where('codigo', $producto->codigo_producto)->get();

                    // Verificamos si tiene inventario para samar
                    $cantidadActualProducto = Inventario::where("codigo", $producto->codigo_producto)->get();
                    $cantidad = 0;
                    if(count($cantidadActualProducto)){
                        $cantidad = $cantidadActualProducto[0]->cantidad + $producto->cantidad;
                    }else{
                        $cantidad = $producto->cantidad;
                    }
                    
                    // Creamos el producto en el inventariop o actualizamos
                    Inventario::updateOrCreate(
                        [
                            "codigo" => $producto->codigo_producto,
                        ],
                        [
                            "descripcion" => $dataProducto[0]->descripcion,
                            "id_marca" => $dataProducto[0]->id_marca,
                            "id_categoria" => $dataProducto[0]->id_categoria,
                            "imagen" => $dataProducto[0]->imagen,
                            "cantidad" => $cantidad,
                            "costo" => $producto->costo,
                            "pvp" => $producto->pvp,
                            "pvp_2" => $producto->pvp_2 ?? $cantidadActualProducto[0]->pvp_2,
                            "pvp_3" => $producto->pvp_3 ?? $cantidadActualProducto[0]->pvp_3,
                            "fecha_entrada" => $request->fecha,
                    ]);

                    $totalArticulos = $totalArticulos  + $producto->cantidad;
                } 
                $resultado['pos'] = Proveedore::where('codigo',  $request->identificacion)->get()[0];

                

                $resultado['carrito'] = $carritos;
                $resultado['hora']  =  date_format(date_create(explode(' ', $resultado->created_at)[1]), 'h:i:s');               
                $resultado['fecha']  =  date_format(date_create(explode(' ', $resultado->created_at)[0]), 'd-m-Y');               
                
                $resultado['totalArticulo']  = $totalArticulos;
                return response()->json([
                    "mensaje" => $mensaje,
                    "data" =>  $resultado, 
                    "estatus" => Response::HTTP_CREATED  
                ], Response::HTTP_CREATED ); 
            }else{
                return response()->json([
                    "mensaje" => $mensaje,
                    "data" =>  $request->request, 
                    "estatus" => Response::HTTP_OK  
                ], Response::HTTP_OK );
            }
    
        } catch (\Throwable $th) {
            $mensaje = Helpers::getMensajeError($th, "Error al intentar registrar factura, ");
            return response()->json([
                "mensaje" => $mensaje,
                "data" =>  $request->request, 
                "estatus" => Response::HTTP_INTERNAL_SERVER_ERROR 
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        
        }
    }

    // Aqui se Factura y procesan las salidas de los producto del inventario
    public function setFacturaSalida(Request $request)
    {
        try {
            
            // Se factura dos veces 
            $resultado = FacturaInventario::create($request->all());
            $resultadoFacturaVenta = Factura::create([
                "codigo" => $request->codigo_factura,
                "razon_social" => $request->razon_social, // nombre de cliente o proveedor
                "identificacion" => $request->identificacion, // numero de documento
                "subtotal" => $request->subtotal, // se guarda en divisas
                "total" => $request->total,
                           "tasa" => $request->tasa, // tasa en el momento que se hizo la transaccion
                           "iva" => $request->iva, // impuesto
                           "tipo" => $request->tipo, // fiscal o no fialcal
                           "concepto" => $request->concepto, // venta, compra ...
                           "descuento" => $request->descuento, // descuento
                           "fecha" => $request->fecha, // fecha venta, compra ...
                           "metodos" => $request->metodos
            ]);
       
            $mensaje = $resultado ? "Se proceso la venta o el movimiento de inventario correctamente correctamente." : "No se registro la factura";
            $estatus = $resultado ? Response::HTTP_CREATED : Response::HTTP_NOT_FOUND;

            if($resultado){
                // Procedemos a descontar del inventario
                $carritos = CarritoInventario::where("codigo", $request->codigo)->get();
                $totalArticulos = 0;

                foreach ($carritos as $key => $producto) {
                    $cantidadActualProducto = Inventario::where("codigo", $producto->codigo_producto)->get()[0]->cantidad;
                    Inventario::where("codigo", $producto->codigo_producto)->update([
                        "cantidad" =>  $cantidadActualProducto - $producto->cantidad,
                    ]);
                    $totalArticulos = $totalArticulos  + $producto->cantidad;
                } 

                $resultado['pos'] = Po::all()[0];
                $resultado['carrito'] = $carritos;
                $resultado['cliente'] = Cliente::where('identificacion', $request->identificacion)->get()[0];
                $resultado['hora']  =  date_format(date_create(explode(' ', $resultado->created_at)[1]), 'h:i:s');               
                $resultado['fecha']  =  date_format(date_create(explode(' ', $resultado->created_at)[0]), 'd-m-Y');               
                
                $resultado['totalArticulo']  = $totalArticulos;
                return response()->json([
                    "mensaje" => $mensaje,
                    "data" =>  $resultado, 
                    "estatus" =>  $estatus   
                ],  $estatus  ); 
            }else{
                return response()->json([
                    "mensaje" => $mensaje,
                    "data" =>  $request->request, 
                    "estatus" =>  $estatus   
                ],  $estatus  );
            }
    
        } catch (\Throwable $th) {
            $mensaje = Helpers::getMensajeError($th, "Error al intentar registrar factura, ");
            return response()->json([
                "mensaje" => $mensaje,
                "data" =>  $request->request, 
                "estatus" => Response::HTTP_INTERNAL_SERVER_ERROR 
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        
        }
    }

    // Validamos que el codigo de factura del proveedor no se repita
    public function getFacturaES(Request $request){

        try {
            $facturaExiste = FacturaInventario::where([
                "identificacion" => $request->identificacion,
                "codigo_factura" => $request->codigo_factura,
            ])->get();
    
            if(count($facturaExiste)){
                return response()->json([
                    "mensaje" => "El código de la factura del proveedor ya esta registrado verifique la factura.",
                    "data" => $facturaExiste,
                    "estatus" => Response::HTTP_CONFLICT
                ], Response::HTTP_CONFLICT);
            }else{
                return response()->json([
                    "mensaje" => "No hay resultado",
                    "data" => $facturaExiste,
                    "estatus" => Response::HTTP_OK
                ], Response::HTTP_OK);
            }
        } catch (\Throwable $th) {
            $mensaje = Helpers::getMensajeError($th, "Error al validar código de facturas ES");
            return response()->json([
                "mensaje" => $mensaje,
                "data" => [],
                "estatus" => Response::HTTP_INTERNAL_SERVER_ERROR
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }


}
