<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Http\Requests\StoreFacturaRequest;
use App\Http\Requests\UpdateFacturaRequest;
use App\Models\Carrito;
use App\Models\CarritoInventario;
use App\Models\Cliente;
use App\Models\DataDev;
use App\Models\FacturaInventario;
use App\Models\Helpers;
use App\Models\Inventario;
use App\Models\Pago;
use App\Models\Po;
use App\Models\Proveedore;
use Barryvdh\DomPDF\Facade\PDF;
use Carbon\Carbon;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class FacturaController extends Controller
{
    public $data;

    function __construct()
    {
        $this->data = new DataDev();
    }

    /**
     * DESPLIEGA LA LISTA DE FACTURAS
     *
     * @return \Illuminate\Http\Response
     */
    public function index(HttpRequest $request)
    {
       
        try {
            $menuSuperior = $this->data->menuSuperior;
            $respuesta = $this->data->respuesta;
            $numeroDePagina = 15;
            $pathname = Request::path();
            
            switch ($request->campo) {
                case 'codigo':
                case 'identificacion':
                    $facturas = Factura::where($request->campo, $request->filtro)
                    ->paginate($numeroDePagina);                
                    break;
                case 'razon_social':
                    $facturas = Factura::where($request->campo, 'like', "%{$request->filtro}%")
                    ->orderBy('razon_social', 'asc')->paginate($numeroDePagina);
                    break;

                default:
                    $facturas = Factura::orderBy('codigo', 'DESC')->paginate($numeroDePagina);
                    break;
            }

            return view('admin.facturas.index', compact('facturas', 'menuSuperior', 'pathname', 'request', 'respuesta'));
        } catch (\Throwable $th) {
            $errorInfo = Helpers::getMensajeError($th, "Error al intentar consultar factura, ");
            return response()->view('errors.404', compact("errorInfo"), 404);
        }
    }
   
 

    /**
     * DESPLIEGA LA VISTA DE LA FACTURA DE MANERA ESPESIFICA
     *
     * @param  \App\Models\Factura  $factura
     * @return \Illuminate\Http\Response
     */
    public function show($id, $moneda = "Bs")
    {
        try {
            $menuSuperior = $this->data->menuSuperior;
            $utilidades = $this->data->utilidades;
            $factura = Factura::find($id);
            if ($moneda == "$") $factura->tasa = 1;
            if ($factura) {
                $factura->carrito = Carrito::where('codigo', $factura->codigo)->get();
                $factura->cliente = Cliente::where('identificacion', $factura->identificacion)->get();
                $contador = 0;
                foreach ($factura->carrito as $key => $producto) {
                    $contador += $producto->cantidad;
                }
                $factura->totalArticulos = $contador;
                $factura['hora']  =  date_format(date_create(explode('T', $factura->fecha)[1]), 'h:i:sa');
                $factura['fecha']  =  date_format(date_create(explode('T', $factura->fecha)[0]), 'd-m-Y');
            } else {
                $mensaje = "El código de la factura no esta registrado, verifique el codigo.";
                $estatus = 404;
                return redirect()->route('admin.facturas.index', compact('mensaje', 'estatus'));
            }


            $pos = count(Po::all()) ? Po::all()[0] : [];
            $pathname = Request::path();
            $pathname = explode('/', $pathname)[0] . '/ver';

            return view('admin.facturas.ver', compact('factura', 'moneda', 'pos', 'utilidades', 'menuSuperior'));
        } catch (\Throwable $th) {
            $errorInfo = Helpers::getMensajeError($th, "Error al intentar consultar factura, ");
            return response()->view('errors.404', compact("errorInfo"), 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Factura  $factura
     * @return \Illuminate\Http\Response
     */
    public function destroy($id_factura)
    {
        try {
            $factura = Factura::find($id_factura);
            Pago::where('codigo_factura', $factura->codigo)->delete();
            FacturaInventario::where('codigo_factura', $factura->codigo)->delete();
            CarritoInventario::where('codigo_factura', $factura->codigo)->delete();
            Carrito::where('codigo', $factura->codigo)->delete();
            $factura->delete();

            /** registramos movimiento al usuario */
            Helpers::registrarMovimientoDeUsuario(request(), Response::HTTP_OK, "Acción de eliminar factura ({$factura->codigo})");

            $estatus = Response::HTTP_OK;
            $mensaje = "Se elimino correctamente la factura.";
            return back()->with(compact('mensaje', 'estatus'));
        } catch (\Throwable $th) {
            $errorInfo = Helpers::getMensajeError($th, "Error al intentar ELIMINAR factura, ");
            return response()->view('errors.404', compact("errorInfo"), 404);
        }
    }



    /** API REST FULL */
    /** 
     * OBTIENE EL ULTIMO CODIGO DE FACTURAS O LA TABLA DESEADA
     * @param tabla string
     * @return \Illuminate\Http\Response string (codigo) 
     */
    public function getCodigoFacturaE($tabla)
    {
        try {
            $codigo = Helpers::getCodigo($tabla, "ENTRADA");
            return response()->json([
                "mensaje" => "Consulta de codigo de factura exitoso",
                "estatus" => Response::HTTP_OK,
                "data" => $codigo
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            $errorInfo = Helpers::getMensajeError($th, "Error en la API al retornar el codigo de la factura,");
            return response()->json([
                "mensaje" => $errorInfo,
                "data" => [],
                "estatus" => Response::HTTP_INTERNAL_SERVER_ERROR
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getCodigoFactura($tabla)
    {
        try {
            $codigo = Helpers::getCodigo($tabla);
            return response()->json([
                "mensaje" => "Consulta de codigo de factura exitoso",
                "estatus" => Response::HTTP_OK,
                "data" => $codigo
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            $errorInfo = Helpers::getMensajeError($th, "Error en la API al retornar el codigo de la factura,");
            return response()->json([
                "mensaje" => $errorInfo,
                "data" => [],
                "estatus" => Response::HTTP_INTERNAL_SERVER_ERROR
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /** 
     * OBTIENE TODOS LOS DATOS DE LA FACTURA POR MEDIO DEL CODIGO
     * @param codigoFactura - string
     * @return \Illuminate\Http\Response object (factura) 
     */
    public function getFacturaAdaptada($codigoFactura)
    {
        try {
            $factura = Factura::where('codigo', $codigoFactura)->get();
            if (count($factura)) {
                $totalArticulos = 0;
                $factura = $factura[0];
                $carritos = Carrito::where('codigo', $factura->codigo)->get();
                foreach ($carritos as $key => $producto) {
                    $producto['stock'] = Inventario::select('cantidad')->where('codigo', $producto->codigo_producto)->get()[0]->cantidad;
                    $totalArticulos = $totalArticulos  + $producto->cantidad;
                }
                $factura['carrito'] = $carritos;

                return response()->json([
                    "mensaje" => "Consulta de factura exitosa.",
                    "data" =>  $factura,
                    "estatus" =>  Response::HTTP_OK
                ],  Response::HTTP_OK);
            } else {
                return response()->json([
                    "mensaje" =>  "No se encontró factura con el código solicitado.",
                    "data" => null,
                    "estatus" => Response::HTTP_OK
                ], Response::HTTP_OK);
            }
        } catch (\Throwable $th) {
            $mensaje = Helpers::getMensajeError($th, "Error al consultar factura, ");
            return response()->json([
                "mensaje" => $mensaje,
                "data" =>  [],
                "estatus" => Response::HTTP_INTERNAL_SERVER_ERROR
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getFactura($codigoFactura)
    {
        try {
         
            $factura = Factura::where('codigo', $codigoFactura)->get();
            if ($factura[0]->concepto == "ANULADA") {
                return response()->json([
                    "mensaje" => "Consulta de factura exitosa.",
                    "data" =>  $factura[0],
                    "estatus" =>  Response::HTTP_OK
                ],  Response::HTTP_OK);
            }

            if (count($factura)) {
                $totalArticulos = 0;
                $factura = $factura[0];
                $carritos = Carrito::where('codigo', $factura->codigo)->get();
                foreach ($carritos as $key => $producto) {
                    $producto['stock'] = Inventario::select('cantidad')->where('codigo', $producto->codigo_producto)->get()[0]->cantidad;
                    $totalArticulos = $totalArticulos  + $producto->cantidad;
                }

                // $carritos = Carrito::where('codigo', $factura->codigo)->get();
                $cliente = Cliente::where('identificacion', $factura->identificacion)->get();
                if (count($cliente)) $factura['cliente'] = $cliente[0];
                else $factura['cliente'] = ["nombre" => "El Cliente fue eliminado"];
                $factura['carrito'] = $carritos;
                $factura['pos'] = Po::all()[0];
                $factura['hora']  =  date_format(date_create(explode('T', $factura->fecha)[1]), 'h:i:sa');
                $factura['fecha']  =  date_format(date_create(explode('T', $factura->fecha)[0]), 'd-m-Y');
                $factura['totalArticulo']  = $totalArticulos;


                return response()->json([
                    "mensaje" => "Consulta de factura exitosa. codigo:" . $codigoFactura,
                    "data" =>  $factura,
                    "estatus" =>  Response::HTTP_OK
                ],  Response::HTTP_OK);
            } else {
                return response()->json([
                    "mensaje" =>  "No se encontró factura con el código solicitado. codigo:" . $codigoFactura,
                    "data" =>  $factura ,
                    "estatus" => Response::HTTP_OK
                ], Response::HTTP_OK);
            }
        } catch (\Throwable $th) {
            $mensaje = Helpers::getMensajeError($th, "Error al consultar factura, ");
            return response()->json([
                "mensaje" => $mensaje . "  codigo:" . $codigoFactura,
                "data" =>  $th,
                "estatus" => Response::HTTP_INTERNAL_SERVER_ERROR
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * REGISTRAR FACTURA
     *
     * @param  \App\Http\Requests\StoreFacturaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HttpRequest $request)
    {
        try {
          
          
            /** eliminamos la factura temporal de concepto anulada para la multicaja */
            Factura::where([
                'codigo' => $request->codigo,
                'concepto' => "ANULADA"
            ])->delete();

            /** consultamos si el codigo de factura existe */
            $codigoExiste = Factura::where([
                'codigo' => $request->codigo,
                'concepto' => "VENTA"
            ])->get();

            /** validamos que el codigo no se repita */
            if (count($codigoExiste) && $request->estatusDeDevolucion == false) {
                return response()->json([
                    "mensaje" => "El codigo de la factura ya EXISTE!",
                    "data" => $codigoExiste[0],
                    "estatus" => Response::HTTP_UNAUTHORIZED
                ], Response::HTTP_UNAUTHORIZED);
            }

            /** Si es una devolución eliminamos la factura y la volvemos a crear con los nuevos datos */
            if ($request['estatusDeDevolucion']) {
                Factura::where('codigo', $request->codigo)->delete();
            }

            /** Creamos la factura */
            $resultado = Factura::create($request->all());

            if ($resultado) {

                /** Consultamos el carrito de compra de la factura */
                $carritos = Carrito::where("codigo", $request->codigo)->get();
                $totalArticulos = 0;

                /** Procedemos a descontar del inventario */
                foreach ($carritos as $key => $producto) {
                    $cantidadActualProducto = Inventario::where("codigo", $producto->codigo_producto)->get()[0]->cantidad;
                    Inventario::where("codigo", $producto->codigo_producto)->update([
                        "cantidad" =>  $cantidadActualProducto - $producto->cantidad
                    ]);
                    $totalArticulos = $totalArticulos  + $producto->cantidad;
                }


                /** Configuramos los datos de la factura para imprimir */
                $resultado['carrito'] = $carritos;
                $resultado['cliente'] = Cliente::where('identificacion', $request->identificacion)->get()[0];
                $resultado['pos'] = Po::all()[0];
                $resultado['hora']  =  date_format(date_create(explode('T', $resultado->fecha)[1]), 'h:i:sa');
                $resultado['fecha']  =  date_format(date_create(explode('T', $resultado->fecha)[0]), 'd-m-Y');
                $resultado['totalArticulo']  = $totalArticulos;

                return Helpers::getRespuestaJson("Factura registrada correctamente", $resultado, Response::HTTP_CREATED);

            } else {
                return Helpers::getRespuestaJson("No se registró la Factura, intente de nuevo.",[], Response::HTTP_NOT_FOUND);
            }
        } catch (\Throwable $th) {
            $mensaje = Helpers::getMensajeError($th, "Error al intentar registrar factura de venta, ");
            return response()->json([
                "mensaje" => $mensaje,
                "data" =>  [],
                "estatus" => Response::HTTP_INTERNAL_SERVER_ERROR
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /** Registrar factura anulada */
    public function setFacturaAnuladaPos(HttpRequest $request)
    {
        try {
                $resultadoFacturaVenta = Factura::updateOrCreate(
                    [
                        "codigo" => $request->codigo
                    ],
                    [
                        "codigo" => $request->codigo,
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
                        "metodos" => $request->metodos,
                        "vendedor" => $request->vendedor,
                    ]
                );
            

            /** Respuesta json */
            return Helpers::getRespuestaJson("La factura del pos venta se anuló correctamente", $resultadoFacturaVenta);
        } catch (\Throwable $th) {
            return Helpers::getRespuestaJson($th->getMessage(), $th, Response::HTTP_NOT_FOUND);
        }
    }


    public function deleteFactura($codigoFactura)
    {
        try {

            Factura::where('codigo', $codigoFactura)->delete();
            return response()->json([
                "mensaje" => "Se eliminó la factura",
                "data" => [],
                "estatus" => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json([
                "mensaje" => "NO se eliminó la factura",
                "data" => [],
                "estatus" => Response::HTTP_NOT_FOUND,
            ], Response::HTTP_NOT_FOUND);
        }
    }
    /** CIERRE DE LA API REST FULL */
}
