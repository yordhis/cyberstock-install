<?php

namespace App\Http\Controllers;

use App\Models\Nota;
use App\Http\Requests\StoreNotaRequest;
use App\Http\Requests\UpdateNotaRequest;
use App\Models\CarritoInventario;
use App\Models\Cliente;
use App\Models\FacturaInventario;
use App\Models\Helpers;
use App\Models\Inventario;
use App\Models\Po;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class NotaController extends Controller
{
    /** API REST FULL */
    // Aqui se Factura  y procesan las entradas de los producto del inventario
    // public function setFacturaEntrada(Request $request)
    // {
    //     try {
    //         /** Obtenemos los productos del carrito */
    //         $carritos = CarritoInventario::where("codigo", $request->codigo)->get();
    //         $totalArticulos = 0;
            
    //         /** Procedemos a sumar del inventario */
    //         foreach ($carritos as $carrito) {
    //             // obtenemos los datos del producto
    //             $dataProducto = Producto::where('codigo', $carrito->codigo_producto)->get();

    //             // Verificamos si tiene inventario para samar
    //             $inventario = Inventario::where("codigo", $carrito->codigo_producto)->get();
    //             $cantidad = 0;
    //             if (count($inventario)) {
    //                 $cantidad = floatval($inventario[0]->cantidad) + floatval($carrito->cantidad);
    //             } else {
    //                 $cantidad = floatval($carrito->cantidad);
    //             }

    //             // Creamos el producto en el inventariop o actualizamos
    //             Inventario::updateOrCreate(
    //                 [
    //                     "codigo" => $carrito->codigo_producto,
    //                 ],
    //                 [
    //                     "descripcion" => $dataProducto[0]->descripcion,
    //                     "id_marca" => $dataProducto[0]->id_marca,
    //                     "id_categoria" => $dataProducto[0]->id_categoria,
    //                     "imagen" => $dataProducto[0]->imagen,
    //                     "cantidad" => $cantidad,
    //                     "costo" => $carrito->costo,
    //                     "pvp" => $carrito->pvp,
    //                     "pvp_2" => $carrito->pvp_2 ?? $inventario[0]->pvp_2,
    //                     "pvp_3" => $carrito->pvp_3 ?? $inventario[0]->pvp_3,
    //                     "fecha_entrada" => date('Y-m-d')
    //                 ]
    //             );

    //             $totalArticulos = $totalArticulos  + $carrito->cantidad;
    //         }

    //         /** Registramos la factura de compra */
    //         $resultado = FacturaInventario::updateOrCreate(
    //             [
    //                 "codigo" => $request->codigo
    //             ],
    //             [
    //                 "codigo" => $request->codigo,
    //                 "codigo_factura" => $request->codigo_factura,
    //                 "razon_social" => $request->razon_social, // nombre de cliente o proveedor
    //                 "identificacion" => $request->identificacion, // numero de documento
    //                 "subtotal" => $request->subtotal, // se guarda en divisas
    //                 "total" => $request->total,
    //                 "tasa" => $request->tasa, // tasa en el momento que se hizo la transaccion
    //                 "iva" => $request->iva, // impuesto
    //                 "tipo" => $request->tipo, // fiscal o no fialcal
    //                 "concepto" => $request->concepto, // venta, compra ...
    //                 "descuento" => $request->descuento, // descuento
    //                 "fecha" => $request->fecha, // fecha venta, compra ...
    //                 "metodos" => $request->metodos
    //             ]
    //         );

    //         $mensaje = $resultado ? "Se proceso la compra correctamente." : "No se registro la factura de compra";
    //         $estatus = $resultado ? Response::HTTP_OK : Response::HTTP_NOT_FOUND;
    //         $resultado['pos'] = Proveedore::where('codigo',  $request->identificacion)->get()[0];
    //         $resultado['carrito'] = $carritos;
    //         $resultado['hora']  =  date_format(date_create(explode('T', $resultado->fecha)[1]), 'h:i:sa');
    //         $resultado['fecha']  =  date_format(date_create(explode('T', $resultado->fecha)[0]), 'd-m-Y');
    //         $resultado['totalArticulo']  = $totalArticulos;

    //         return response()->json([
    //             "mensaje" => $mensaje,
    //             "data" =>  $resultado,
    //             "estatus" => Response::HTTP_CREATED
    //         ], Response::HTTP_CREATED);
    //     } catch (\Throwable $th) {
    //         $mensaje = Helpers::getMensajeError($th, "Error al intentar registrar factura, ");
    //         return response()->json([
    //             "mensaje" => $mensaje,
    //             "data" =>  $request->request,
    //             "estatus" => Response::HTTP_INTERNAL_SERVER_ERROR
    //         ], Response::HTTP_INTERNAL_SERVER_ERROR);
    //     }
    // }

    // Aqui se Factura y procesan las salidas de los producto del inventario
    public function setFacturaSalida(Request $request)
    {
        try {
            /** Creamos las facturas anuladas */
            $resultado = FacturaInventario::updateOrCreate(
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

            if ($request->concepto != 'CONSUMO') {
                $resultadoFacturaVenta = Nota::updateOrCreate(
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
                    ]
                );
            } else {
                /** eliminamos la factura temporal de concepto anulada para la multicaja */
                Nota::where([
                    'codigo' => $request->codigo_factura,
                    'concepto' => "ANULADA"
                ])->delete();
            }

            $mensaje = $resultado ? "Se proceso la venta o el movimiento de inventario correctamente." : "No se registro la factura";
            $estatus = $resultado ? Response::HTTP_CREATED : Response::HTTP_NOT_FOUND;

            if ($resultado) {
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
                ],  $estatus);
            } else {
                return response()->json([
                    "mensaje" => $mensaje,
                    "data" =>  $request->request,
                    "estatus" =>  $estatus
                ],  $estatus);
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
}
