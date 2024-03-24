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
            
            $resultado = FacturaInventario::create($request->all());
            $mensaje = $resultado ? "Se proceso la compra correctamente correctamente." : "No se registro la factura de compra";
            $estatus = $resultado ? Response::HTTP_OK : Response::HTTP_NOT_FOUND;
            
            if($resultado){
                // Procedemos a sumar del inventario
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
                            "fecha_entrada" => date('Y-m-d'),
                    ]);

                    $totalArticulos = $totalArticulos  + $producto->cantidad;
                } 

                $resultado['pos'] = Proveedore::where('codigo',  $request->identificacion)->get()[0];
                $resultado['carrito'] = $carritos;
                $resultado['hora']  =  date_format(date_create(explode('T', $resultado->fecha)[1]), 'h:i:sa');               
                $resultado['fecha']  =  date_format(date_create(explode('T', $resultado->fecha)[0]), 'd-m-Y');             
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
                /** Creamos las facturas anuladas */
                $resultado =FacturaInventario::updateOrCreate(
                    [
                        "codigo" => $request->codigo
                    ],
                    [
                        "codigo" => $request->codigo,
                        "codigo_factura" => $request->codigo_factura,
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
                    ]
                );

                if($request->concepto != 'CONSUMO'){
                    $resultadoFacturaVenta = Factura::updateOrCreate(
                        [
                            "codigo" => $request->codigo_factura
                        ],
                        [
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
                }else{
                    /** eliminamos la factura temporal de concepto anulada para la multicaja */
                    Factura::where([
                        'codigo' => $request->codigo_factura,
                        'concepto' => "ANULADA"
                    ])->delete();
                }
       
            $mensaje = $resultado ? "Se proceso la venta o el movimiento de inventario correctamente." : "No se registro la factura";
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
                $resultado['hora']  =  date_format(date_create(explode('T', $resultado->fecha)[1]), 'h:i:s');               
                $resultado['fecha']  =  date_format(date_create(explode('T', $resultado->fecha)[0]), 'd-m-Y');               
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

    /** Registrar factura anulada */
    public function setFacturaAnulada(Request $request){
        try {
            /** Creamos las facturas anuladas */
            $resultado =FacturaInventario::updateOrCreate(
                    [
                        "codigo" => $request->codigo
                    ],
                    [
                        "codigo" => $request->codigo,
                        "codigo_factura" => $request->codigo_factura,
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
                    ]
            );

            if($request->concepto != 'CONSUMO'){
                $resultadoFacturaVenta = Factura::updateOrCreate(
                    [
                        "codigo" => $request->codigo_factura
                    ],
                    [
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
            }

            /** Respuesta json */
            return Helpers::getRespuestaJson("La factura se anuló correctamente", $resultado);

   
        } catch (\Throwable $th) {
            return Helpers::getRespuestaJson($th->getMessage(), $th, Response::HTTP_NOT_FOUND);
        }
    }

    /** eliminar factura temporal */
    public function deleteFacturaInventario($codigoFactura){
        try {
          
            FacturaInventario::where('codigo', $codigoFactura)->delete();
            
            /** Respuesta json */
            return Helpers::getRespuestaJson("Se eliminó la factura inventario");
        } catch (\Throwable $th) {
            /** Respuesta json */
            return Helpers::getRespuestaJson("NO se eliminó la factura inventario", [], Response::HTTP_NOT_FOUND);
        }
    }

}
